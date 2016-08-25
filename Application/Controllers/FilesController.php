<?php
require_once('BaseController.php');
class FilesController extends BaseController
{
    public function Details()
    {
        $args = $this->Parameters;
        $virtualDirectory = $this->GetVirtualDirectory($args, $this->GetCurrentUser());

        $this->Title = end($args);

        if(!$virtualDirectory == null){
            $this->ToggleFolder($virtualDirectory->Id);
        }

        $this->Set('VirtualDirectory', $virtualDirectory);
        return $this->View();
    }

    public function Upload($parentDirectoryId = 0)
    {
        $this->Title = 'Upload file';

        if(!$this->CanUploadFile()){
            return $this->HttpNotFound();
        }

        if($this->IsPost() && !$this->Data->IsEmpty()){
            $document = $this->Data->Parse('Document', $this->Models->Document);
            var_dump($document->Object());

            
            $uploadedFile = $this->Files['UploadedFile'];
            var_dump($uploadedFile);

        }else{
            $parentDirectory = $this->Models->VirtualDirectory->Find($parentDirectoryId);
            if($parentDirectory == null){
                return $this->HttpNotFound();
            }

            $dbVirtualDirectories = $this->Models->VirtualDirectory->All();
            $virtualDirectories = array();

            $virtualDirectories[0] = $this->Html->SafeHtml('<root>');
            foreach($dbVirtualDirectories as $dbVirtualDirectory){
                $virtualDirectories[$dbVirtualDirectory->Id] = $dbVirtualDirectory->GetFullPath();
            }

            $this->Set('VirtualDirectories', $virtualDirectories);

            $currentUser = $this->GetCurrentUser();
            $document = $this->Models->Document->Create(array('OwnerId' => $currentUser['Id'], 'DirectoryId' => $parentDirectory->Id));
            $this->Set('Document', $document);

            return $this->View();
        }
    }

    public function CanUploadFile()
    {
        if($this->IsLoggedIn()) {
            return true;
        }

        return false;
    }

    public function CanCreateFolder()
    {
        if($this->IsLoggedIn()) {
            return true;
        }

        return false;
    }

    private function GetVirtualDirectory($path, $currentUser)
    {
        if(!is_array($path)){
            return null;
        }
        $directories = $this->Models->VirtualDirectory->Where(array('ParentDirectoryId' => null));
        $directory = null;

        foreach($path as $name){
            $directory = $directories->Where(array('Name' => $name))->First();
            if($directory == null){
                return null;
            }

            // Check user priviles for every directory in the file hierarchy
            if(!$this->CheckUserPrivileges($directory, $currentUser)) {
                return null;
            }

            $directories = $directory->VirtualDirectories->Where(array('ParentDirectoryId' => $directory->Id));
        }

        return $directory;
    }

    private function CheckUserPrivileges($virtualDirectory, $currentUser)
    {
        return true;
    }
}