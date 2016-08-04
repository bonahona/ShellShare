<?php
require_once('AdminController.php');
class DocumentsController extends Controller
{
    public function Create($virtualDirectoryPath)
    {
        $virtualDirectory = $this->GetVirtualDirectory($virtualDirectoryPath);

        if($virtualDirectory == null){
            return $this->HttpNotFound();
        }

        $this->Set('VirtualDirectory', $virtualDirectory);
        return $this->View();
    }

    private function GetVirtualDirectory($path)
    {
        if(!is_array($path)){
            return null;
        }

        $directories = $this->Models->VirtualDirectory->Where(array('ParentDirectoryId' => null));

        foreach($path as $name){
            $directory = $directories->Where(array('Name' => $name))->First;
            if($directory == null){
                return null;
            }
        }
    }
}