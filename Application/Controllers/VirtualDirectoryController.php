<?php
require_once('BaseController.php');
class VirtualDirectoryController extends BaseController
{
    public function Create($parentDirectoryId = null)
    {
        $this->Title = 'Create virtual directory';

        if($parentDirectoryId !== ''){
            $parentDirectory = $this->Models->VirtualDirectory->Find($parentDirectoryId);
            if($parentDirectory == null){
                return $this->HttpNotFound();
            }
        }else{
            $parentDirectory = null;
        }

        if($this->IsPost() && !$this->Data->IsEmpty()){
            $virtualDirectory = $this->Data->Parse('VirtualDirectory', $this->Models->VirtualDirectory);
            $virtualDirectory->ConvertZeroToNull();

            $virtualDirectory->AccessRightsMask = $this->ConvertRightsToMask(array());
            $virtualDirectory->Save();
            return $this->Redirect($virtualDirectory->GetLinkPath());
        }else{
            $currentUser = $this->GetCurrentUser();

            if($parentDirectory == null){
                $virtualDirectory = $this->Models->VirtualDirectory->Create(array('DirectoryId' => null, 'OwnerId' => $currentUser['Id']));
            }else {
                $virtualDirectory = $this->Models->VirtualDirectory->Create(array('DirectoryId' => $parentDirectory->Id, 'OwnerId' => $currentUser['Id']));
            }
            $dbVirtualDirectories = $this->Models->VirtualDirectory->All();

            $virtualDirectories = array();

            $virtualDirectories[0] = $this->Html->SafeHtml('<root>');
            foreach($dbVirtualDirectories as $virtualDirectory){
                $virtualDirectories[$virtualDirectory->Id] = $virtualDirectory->GetFullPath();
            }

            $this->Set('VirtualDirectories', $virtualDirectories);
            $this->Set('VirtualDirectory', $virtualDirectory);

            return $this->View();
        }
    }

    private function ConvertRightsToMask($values)
    {
        $result = 0;

        foreach($values as $value){

        }

        return $result;
    }

    private function ConvertRightsToList($mask)
    {

    }

    private function GetRights()
    {
        // Copy of UNIX style rights. Read means viewing the catalogue from the outside and execute means to enter the catalogue to read its content
        $result = array(
            'AnonymousRead' => 1,
            'AnonymousExecute' => 2
        );

        return $result;
    }
}