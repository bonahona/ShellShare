<?php
class ShellAuthHelper implements  IHelper
{
    public $ApplicationName;
    public $PublicKey;

    public $ShellAuthServer;
    public $ShellAuthPort;
    public $ShellAuthMethodPath;
    public $Controller;

    public function Init($config, $controller)
    {
        $this->ApplicationName = $config['ShellApplication']['Name'];
        $this->PublicKey = $config['ShellApplication']['PublicKey'];

        $this->ShellAuthServer = $config['ShellAuthServer']['Server'];
        $this->ShellAuthPort = $config['ShellAuthServer']['Port'];
        $this->ShellAuthMethodPath = $config['ShellAuthServer']['MethodPath'];

        $this->Controller = $controller;
    }

    public function CreateApplication($application)
    {
        $payLoad = array(
            'ShellApplication' => $application
        );

        $callPath = $this->GetApplicationPath();

        return $this->SendToServer($payLoad, $callPath);
    }

    public function EditApplication($application)
    {
        $payload = array(
            'ShellApplication' => $application
        );

        $callPath = $this->GetApplicationPath('EditApplication');
        return $this->SendToServer($payload, $callPath);
    }

    public function DeleteApplication($id)
    {
        $payLoad = $id;
        $callPath = $this->GetApplicationPath('DeleteApplication');
        return $this->SendToServer($payLoad, $callPath);
    }

    public function GetApplication($id = null)
    {
        if($id == null){
            $payLoad = array();
        }else{
            $payLoad = array(
                'Id' => $id
            );
        }

        $callPath = $this->GetApplicationPath('GetApplication');
        return $this->SendToServer($payLoad, $callPath);
    }

    public function CreateUser($shellUser)
    {
        $payLoad = array(
            'ShellUser' => $shellUser
        );

        $callPath = $this->GetApplicationPath('CreateUser');
        return $this->SendToServer($payLoad, $callPath);
    }

    public function EditUser($shellUser)
    {
        $payLoad = array(
            'ShellUser' => $shellUser
        );

        $callPath = $this->GetApplicationPath('EditUser');
        return $this->SendToServer($payLoad, $callPath);
    }

    public function ResetPassword($userId, $password)
    {
        $payLoad = array(
            'ShellUser' => array(
                'Id' => $userId,
                'Password' => $password
            )
        );

        $callPath = $this->GetApplicationPath('ResetPassword');
        return $this->SendToServer($payLoad, $callPath);
    }

    public function Login($username, $password)
    {
        $payLoad = "mutation{
	Login(
		username: \"$username\",
		password: \"$password\",
		application: \"$this->ApplicationName\"
	){
		Guid,
		Expires,
		Issued,
		ShellUserPrivilege{
			ShellUser{
				Id,
				DisplayName,
				Username,
				IsActive
			}
		}
	}
}";

        $response =  $this->SendToServer($payLoad);

        if(count($response['errors']) == 0){
            $this->Controller->Session['SessionToken'] = $response['data']['Login']['Guid'];
            $user = $response['data']['Login']['ShellUserPrivilege']['ShellUser'];
            $this->Controller->SetLoggedInUser($user);

            // Check if a local user exists, and if not, create on
            $userId = $user['Id'];
            if(!$this->Controller->Models->LocalUser->Any(array('ShellUserId' => $userId))){
                $localUser = $this->Controller->Models->LocalUser->Create(array('ShellUserId' => $userId));
                $localUser->Save();
            }
        }

        return $response;
    }

    public function Logout($accessToken = null)
    {
        if($accessToken == null){
            $accessToken = $this->Controller->Session['SessionToken'];
        }
        $payLoad = array(
            'AccessToken' => $accessToken
        );

        $callPath = $this->GetApplicationPath('Logout');
        $this->Controller->Session->Destroy();
        return $this->SendToServer($payLoad, $callPath);
    }

    public function CheckAccessToken($accessToken = null)
    {
        if($accessToken == null){
            $accessToken = $this->Controller->Session['SessionToken'];
        }
        $payLoad = array(
            'AccessToken' => $accessToken
        );

        $callPath = $this->GetApplicationPath('CheckAccessToken');
        return $this->SendToServer($payLoad, $callPath);
    }

    public function GetUser($id = null)
    {
        if($id != null){
            $payLoad = array(
                'Id' => $id
            );
        }else{
            $payLoad = array();
        }

        $callPath = $this->GetApplicationPath('GetUser');

        return $this->SendToServer($payLoad, $callPath);
    }

    public function GetLocalUsers()
    {
        $callPath = $this->GetApplicationPath('GetLocalUsers');
        return $this->SendToServer(array(), $callPath);
    }

    public function SetPrivilegeLevel($userLevel, $userId, $applicationId = null)
    {
        $payLoad = array(
            'ShellUserPrivilege' => array(
                'ShellUserId' => $userId,
                'UserLevel' => $userLevel
            )
        );

        if($applicationId != null){
            $payLoad['ShellUserPrivilege']['ShellApplicationId'] = $applicationId;
        }

        $callPath = $this->GetApplicationPath('SetPrivilegeLevel');
        return $this->SendToServer($payLoad, $callPath);
    }

    public function GetUserApplicationPrivileges($userId)
    {
        $payLoad = array(
            'Id' => $userId
        );

        $callPath = $this->GetApplicationPath('GetUserApplicationPrivileges');
        return $this->SendToServer($payLoad, $callPath);
    }

    protected function GetApplicationPath()
    {
        $result = 'http://' . $this->ShellAuthServer . ":" . $this->ShellAuthPort . $this->ShellAuthMethodPath;

        return $result;
    }

    protected function SendToServer($payload)
    {
        $callPath = $this->GetApplicationPath();
        $data = ['query' => $payload];

        $data = json_encode($data);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $callPath);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, "ShellAuthConnector");
        curl_setopt($curl, CURLOPT_HTTPHEADER,     array('Content-Type: text/plain'));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        if(!$response = curl_exec($curl)){
            $curlError = curl_error($curl);
            //$curlErrorCode = curl_errno($curl);

            return array(
                'data' => [],
                'errors' => [
                    $curlError
                ]
            );
        }

        curl_close($curl);

        return json_decode($response, true);
    }
}