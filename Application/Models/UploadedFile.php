<?php
class UploadedFile extends Model
{
    public $TableName = 'uploadedfile';

    public function GetName()
    {
        return $this->Document->Name . '.' . $this->GetFileEnding();
    }

    public function GetOldName()
    {
        $timeStamp = date('Y-m-d_G-i', $this->CreateDate);
        return $this->Document->Name . '_' . $timeStamp . '.' . $this->GetFileEnding();
    }

    public function GetDirectDownloadLink()
    {
        return '/DownloadHistory/' . $this->Id;
    }

    public function GetLastUpdated()
    {
        $result = date('Y-m-d G:i', $this->CreateDate);
        return $result;
    }

    public function GetFileEnding()
    {
        return $this->FileExtension;
    }

    public function GetUploadedBy()
    {
        $uploadingUser = $this->Helpers->ShellAuth->GetUser($this->UploadedById);

        if($uploadingUser['Error'] != 0){
            return '';
        }else{
            return $uploadingUser['Data'][0]['DisplayName'];
        }
    }
}