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

    public function GetDirectoryContents($filePath)
    {
        $args = func_get_args();
        $args = array_slice($args, 0, count($args) -1);

        $currentUser = $this->GetCurrentUser();
        $virtualDirectory = $this->GetVirtualDirectory($args, $currentUser);
        if($virtualDirectory == null){
            $result = array(
                'found' => false
            );

            return $this->Json($result);
        }

        $documents = array();
        $dbDocuments = $virtualDirectory->Documents;
        foreach($dbDocuments as $dbDocument){
            $document = array(
                'name' => $dbDocument->Name
            );
            $documents[$dbDocument->Name] = $document;
        }

        $result = array(
            'found' => true,
            'documents' => $documents
        );

        return $this->Json($result);
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
            if(!$this->CheckUserPrivileges($directory, $currentUser)){
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