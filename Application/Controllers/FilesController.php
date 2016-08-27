<?php
require_once('BaseController.php');
class FilesController extends BaseController
{
    public function Details()
    {
        $path = $this->Parameters;
        $this->Title = end($path);

        $node = $this->GetNode($path, $this->GetCurrentUser());

        if($node == null){
            return $this->HttpNotFound();
        } else if(is_a($node, 'VirtualDirectory')){
            $this->ToggleFolder($node->Id);
            $this->Set('VirtualDirectory', $node);
            return $this->View('DirectoryDetails');
        }else if(is_a($node, 'Document')){
            $this->Set('Document', $node);
            return $this->View('DocumentDetails');
        }

        // Fallback
        return $this->HttpNotFound();
    }

    public function Download()
    {
        $path = $this->Parameters;
        $document = $this->GetNode($path, $this->GetCurrentUser());

        if(!is_a($document, 'Document')){
            return $this->HttpNotFound();
        }

        $uploadedFile = $document->GetCurrentFile();

        header('Content-Type: ' . $uploadedFile->MimeType);
        $content = file_get_contents($uploadedFile->LocalFilePath, FILE_USE_INCLUDE_PATH);
        echo $content;
    }

    public function DownloadHistory($uploadedFileId = null)
    {
        if($uploadedFileId == null){
            return $this->HttpNotFound();
        }

        $uploadedFile = $this->Models->UploadedFile->Find($uploadedFileId);
        if($uploadedFile == null){
            return $this->HttpNotFound();
        }

        header('Content-Type: ' . $uploadedFile->MimeType);
        $content = file_get_contents($uploadedFile->LocalFilePath, FILE_USE_INCLUDE_PATH);
        echo $content;
    }

    public function History()
    {
        $path = $this->Parameters;
        $this->Title = end($path) . ' history';

        $document = $this->GetNode($path, $this->GetCurrentUser());

        if(!is_a($document, 'Document')){
            return $this->HttpNotFound();
        }

        $this->Set('Document', $document);
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
            
            $file = $this->Files['UploadedFile'];

            $fileName = uniqid();
            $directory = '/Upload/Files/';
            $fileExtension = $file->GetFileExtension();

            if(!is_dir($directory)){
                mkdir($directory, 777, true);
            }

            $completePath = $directory . $fileName . '.' . $fileExtension;
            $currentUser = $this->GetCurrentUser();
            $now = time();

            if($file->Save($completePath)){
                $document->Save();
                $uploadedFile = $this->Models->UploadedFile->Create();
                $uploadedFile->LocalFilePath = $completePath;
                $uploadedFile->CreateDate = $now;
                $uploadedFile->MimeType = $file->Type;
                $uploadedFile->FileExtension = $fileExtension;
                $uploadedFile->DocumentId = $document->Id;
                $uploadedFile->UploadedById = $currentUser['Id'];
                $uploadedFile->Save();

                $redirectPath = $document->GetHistoryPath();
                return $this->Redirect($redirectPath);
            }

            $this->Set('Document', $document);
            return $this->View();

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

    public function Update($documentId = null)
    {
        if($documentId == null){
            return $this->HttpNotFound();
        }

        $document = $this->Models->Document->Find($documentId);
        if($document == null){
            return $this->HttpNotFound();
        }

        $this->Title = $document->Name;

        if($this->IsPost() && !$this->Data->IsEmpty()){
            $file = $this->Files['UploadedFile'];

            $fileName = uniqid();
            $directory = '/Upload/Files/';
            $fileExtension = $file->GetFileExtension();

            if(!is_dir($directory)){
                mkdir($directory, 777, true);
            }

            $completePath = $directory . $fileName . '.' . $fileExtension;
            $currentUser = $this->GetCurrentUser();
            $now = time();

            if($file->Save($completePath)){
                $document->Save();
                $uploadedFile = $this->Models->UploadedFile->Create();
                $uploadedFile->LocalFilePath = $completePath;
                $uploadedFile->CreateDate = $now;
                $uploadedFile->MimeType = $file->Type;
                $uploadedFile->FileExtension = $fileExtension;
                $uploadedFile->DocumentId = $document->Id;
                $uploadedFile->UploadedById = $currentUser['Id'];
                $uploadedFile->Save();

                $redirectPath = $document->GetHistoryPath();
                return $this->Redirect($redirectPath);
            }

            return $this->View();
        }else{
            $this->Set('Document', $document);

            $uploadedFile = $this->Models->UploadedFile->Create(array('DocumentId' => $documentId));
            $this->Set('UploadedFile', $uploadedFile);
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

    private function GetNode($path, $currentUser)
    {
        $virtualDirectory = $this->GetVirtualDirectory($path, $this->GetCurrentUser());

        if(!$virtualDirectory == null && is_a($virtualDirectory, 'VirtualDirectory')){
            return $virtualDirectory;
        }

        if($virtualDirectory == null){
            $document = $this->GetDocument($path, $this->GetCurrentUser());
           return $document;
        }

        return null;
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

    private function GetDocument($path, $currentUser)
    {
        if(!is_array($path)){
            return null;
        }

        // Strip away the last entry
        $fileName = end($path);
        array_pop($path);

        $parentDirectory = $this->GetVirtualDirectory($path, $currentUser);
        if($parentDirectory == null){
            return null;
        }

        $document = $parentDirectory->Documents->Where(array('Name' => $fileName))->First();
        return $document;
    }

    private function CheckUserPrivileges($virtualDirectory, $currentUser)
    {
        return true;
    }
}