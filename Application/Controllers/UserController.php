<?php
require_once('BaseController.php');
class UserController extends BaseController
{
    public function BeforeAction()
    {
        if(!$this->IsLoggedIn() && !$this->Action == "Login"){
            $this->Redirect('/User/Login', array('ref' => $this->RequestUri));
        }
    }

    public function Login()
    {
        $this->Title = 'Login';
        $this->Layout = 'Login';

        if($this->IsPost()) {
            $user = $this->Data->RawParse('User');

            $response = $this->Helpers->ShellAuth->Login($user['Username'], $user['Password']);

            if(isset($response['errors'])){
                foreach($response['errors'] as $error){
                    $this->ModelValidation->AddError('User', 'Password', $error);
                }
            }

            $ref = $this->Get['ref'];
            if($this->ModelValidation->Valid()) {

                $shellUserId = $response['data']['Login']['ShellUserPrivilege']['ShellUser']['Id'];

                if($ref == null || $ref == ""){
                    return $this->Redirect('/');
                }else{
                    return $this->Redirect($ref);
                }
            }

            $this->Set('User', $user);
            return $this->View();
        }else{
            return $this->View();
        }
    }

    public function Logout()
    {
        $this->Helpers->ShellAuth->Logout();
        return $this->Redirect('/');
    }
}