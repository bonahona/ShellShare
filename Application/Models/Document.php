<?php
class Document extends Model
{
    public $TableName = 'document';

    public function OnSave()
    {
        $this->NavigationName = strtolower($this->Name);
    }

    public function GetDownloadPath()
    {
        return '/Download/' . $this->GetFullPath() . '/';
    }

    public function GetHistoryPath()
    {
        return '/History/' . $this->GetFullPath() . '/';
    }

    public function GetUpdatePath()
    {
        return '/Update/' . $this->Id;
    }

    public function GetDeletePath()
    {
        return '/Update/' . $this->Id;
    }

    public function GetEditPath()
    {
        return '/Edit/' . $this->Id;
    }

    public function GetFullPath()
    {
        return $this->Directory->GetFullPath() . '/' . $this->NavigationName;
    }

    public function GetName()
    {
        return $this->Name . '.' . $this->GetFileEnding();
    }

    public function GetLastUpdated()
    {
        $currentFile = $this->GetCurrentFile();
        return $currentFile->GetLastUpdated();
    }

    public function GetFileEnding()
    {
        $currentFile = $this->GetCurrentFile();
        return $currentFile->GetFileEnding();
    }

    public function GetUploadedBy()
    {
        $currentFile = $this->GetCurrentFile();
        return $currentFile->GetUploadedBy();
    }

    public function GetCurrentFile()
    {
        $lastUploadedFile = $this->UploadedFiles->OrderByDescending('Id')->First();
        return $lastUploadedFile;
    }

    public function GetShortDesciption($maxLength = 30)
    {
        $textLength = strlen($this->ShortDescription);
        if($textLength <= $maxLength){
            return $this->ShortDesciption;
        }else{
            $subString = substr($this->ShortDescription, 0, $maxLength) . '...';
            return $subString;
        }
    }

    public function GetSearchResultContext()
    {
        return $this->GetShortDesciption(50);
    }
}