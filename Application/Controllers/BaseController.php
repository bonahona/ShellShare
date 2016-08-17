<?php
class BaseController extends Controller
{
    public $RootDirectories;
    public $VirtualDirectories;

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
            $this->Logging->Write($userPrivileges);
        }
    }

    protected function IsFolderOpen($folderPath)
    {
        return true;
    }
}
