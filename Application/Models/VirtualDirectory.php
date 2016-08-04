<?php
class VirtualDirectory extends Model
{
    public $TableName = 'virtualdirectory';

    public function GetLinkPath()
    {
        return '/Files/Details/' . $this->GetFullPath();
    }

    public function GetFullPath()
    {
        // Traverse the parent tree until a root is found
        $parentDirectories = array();
        $currentFolder = $this;

        while($currentFolder != null){
            $parentDirectories[] = $currentFolder->Name;
            $currentFolder = $currentFolder->ParentDirectory;
        }

        $parentDirectories = array_reverse($parentDirectories);

        $result = implode('/', $parentDirectories);

        return $result;
    }
}