<?php
class BaseController extends Controller
{
    public $RootDirectories;
    public $VirtualDirectories;
    public $UserPrivileges;

    public function BeforeAction()
    {
        $this->RootDirectories = $this->Models->VirtualDirectory->Where(array('ParentDirectoryId' => null));
        $this->Set('RootDirectories', $this->RootDirectories);

        $this->VirtualDirectories = $this->Models->VirtualDirectory->All();
        $this->Set('VirtualDirectories', $this->VirtualDirectories);

        $user = $this->GetCurrentUser();
        $this->Logging->Write($user);

        if($user != null) {
            $userPrivileges = $this->Helpers->ShellAuth->GetUserApplicationPrivileges($user['Id']);

            $this->UserPrivileges = $userPrivileges['Data'];
            $this->Set('UserPrivileges', $this->UserPrivileges);
        }
    }

    public function IsAdmin()
    {
        if($this->UserPrivileges == null){
            return false;
        }

        if($this->UserPrivileges['UserLevel'] > 0){
            return true;
        }else{
            return false;
        }
    }

    public function ToggleFolder($folderId)
    {
        if(!isset($_SESSION['OpenFolders'])){
            $_SESSION['OpenFolders'] = array();
        }

        // Toggle the state of the folder
        $_SESSION['OpenFolders'][$folderId] = !$_SESSION['OpenFolders'][$folderId];
    }

    public function IsFolderOpen($folderId)
    {
        if(!isset($_SESSION['OpenFolders'])){
            return false;
        }

        if(!isset($_SESSION['OpenFolders'][$folderId])){
            return false;
        }

        return $_SESSION['OpenFolders'][$folderId];
    }
}
