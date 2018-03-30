<?php
require_once('BaseController.php');
class FilesController extends BaseController
{
    public function Details()
    {
        $path = $this->Parameters;

        if(count($path) > 0) {
            $this->Title = 'Share - ' . end($path);
        }else{
            $this->Title = 'Share';
        }

        if(count($path) == 0){
            return $this->View('DisplayRoot');
        }

        $pathResult = $this->GetNode($path, $this->GetCurrentUser());

        $node = $pathResult['Node'];





        if($node == null){
            return $this->HttpNotFound();
        } else if(is_a($node, 'VirtualDirectory')){
            $this->ToggleFolder($node->Id);

            $this->MapUsersForDirectory($node);

            $this->Set('VirtualDirectory', $node);
            return $this->View('DirectoryDetails');
        }else if(is_a($node, 'Document')){
            $this->Set('Document', $node);
            return $this->View('DocumentDetails');
        }

        // Fallback
        return $this->HttpNotFound();
    }

    private function MapUsersForDirectory($node)
    {
        $ids = [];
        foreach($node->Documents as $document){
            $ids[] = $document->OwnerId;
        }

        $users = $this->Helpers->ShellAuth->GetUsersById($ids);
        if($users == null){
            return;
        }

        $users = array_values($users['data']);

        $count = 0;
        foreach($node->Documents as $document){
            $document->AuthUser = $users[$count];

            $count ++;
        }
    }

    private function MapUsersForDocument($node)
    {
        $ids = [];
        foreach($node->UploadedFiles as $uploadedFile){
            $ids[] = $uploadedFile->UploadedById;
        }

        $users = $this->Helpers->ShellAuth->GetUsersById($ids);
        $users = array_values($users['data']);

        $count = 0;
        foreach($node->UploadedFiles as $uploadedFile){
            $uploadedFile->AuthUser = $users[$count];

            $count ++;
        }
    }

    public function Download()
    {
        $path = $this->Parameters;
        $document = $this->GetNode($path, $this->GetCurrentUser());
		$document = $document['Node'];

        if(!is_a($document, 'Document')){
            return $this->HttpNotFound();
        }

        $uploadedFile = $document->GetCurrentFile();
        
        $response = new HttpResult();
        $response->Content = file_get_contents('/var/www/html/' . $uploadedFile->LocalFilePath, FILE_USE_INCLUDE_PATH);
        $response->MimeType = $uploadedFile->MimeType;

        return $response;
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

        $response = new HttpResult();
        $response->Content = file_get_contents('/var/www/html/' . $uploadedFile->LocalFilePath, FILE_USE_INCLUDE_PATH);
        $response->MimeType = $uploadedFile->MimeType;

        return $response;
    }

    public function History()
    {
        $path = $this->Parameters;
        $this->Title = end($path) . ' history';

        $document = $this->GetNode($path, $this->GetCurrentUser());
		$document = $document['Node'];
		
        if(!is_a($document, 'Document')){
            return $this->HttpNotFound();
        }

        $this->MapUsersForDocument($document);
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

            $directory = '/uploads/files/';

            if($file != null) {
                $fileName = uniqid();
                $fileExtension = $file->GetFileExtension();

                $completePath = $directory . $fileName . '.' . $fileExtension;
                $currentUser = $this->GetCurrentUser();
                $now = time();

                if ($file->Save($completePath)) {
                    $document->Save();
                    $uploadedFile = $this->Models->UploadedFile->Create();
                    $uploadedFile->LocalFilePath = $completePath;
                    $uploadedFile->CreateDate = $now;
                    $uploadedFile->MimeType = $file->Type;
                    $uploadedFile->FileExtension = $fileExtension;
                    $uploadedFile->DocumentId = $document->Id;
                    $uploadedFile->UploadedById = $currentUser['LocalUser'];
                    $uploadedFile->Save();

                    $redirectPath = $document->GetHistoryPath();
                    return $this->Redirect($redirectPath);
                }
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
            $document = $this->Models->Document->Create(array('OwnerId' => $currentUser['LocalUser'], 'DirectoryId' => $parentDirectory->Id));
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
            $directory = '/uploads/files/';
            $fileExtension = $file->GetFileExtension();

            if(!is_dir($directory)){
                mkdir($directory, 0777, true);
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

    public function Edit($id = null)
    {
        if($id == null){
            return $this->HttpNotFound();
        }

        $document = $this->Models->Document->Find($id);
        if($document == null){
            return $this->HttpNotFound();
        }

        if(!$this->CanEditDocument($id)){
            return $this->HttpNotFound();
        }

        $this->Title = 'Edit ' . $document->Name;

        if($this->IsPost() && !$this->Data->IsEmpty()){
            $document = $this->Data->DbParse('Document', $this->Models->Document);
            $document->Save();

            $redirectUrl = $document->GetHistoryPath();
            return $this->Redirect($redirectUrl);
        }else{
            $dbVirtualDirectories = $this->Models->VirtualDirectory->All();
            $virtualDirectories = array();

            $virtualDirectories[0] = $this->Html->SafeHtml('<root>');
            foreach($dbVirtualDirectories as $dbVirtualDirectory){
                $virtualDirectories[$dbVirtualDirectory->Id] = $dbVirtualDirectory->GetFullPath();
            }

            $this->Set('VirtualDirectories', $virtualDirectories);
            $this->Set('Document', $document);
            return $this->View();
        }
    }

    public function EditDirectory($id = null)
    {
        if($id == null){
            return $this->HttpNotFound();
        }

        $virtualDirectory = $this->Models->VirtualDirectory->Find($id);
        if($virtualDirectory == null){
            return $this->HttpNotFound();
        }

        if(!$this->CanEditDirectory($id)){
            return $this->HttpNotFound();
        }

        $this->Title = 'Edit directory ' . $virtualDirectory->Name;

        if($this->IsPost() && !$this->Data->IsEmpty()){
            $virtualDirectory = $this->Data->DbParse('VirtualDirectory', $this->Models->VirtualDirectory);
            $virtualDirectory->Save();

            $redirectUrl = $virtualDirectory->GetLinkPath();
            return $this->Redirect($redirectUrl);
        }else{
            $dbVirtualDirectories = $this->Models->VirtualDirectory->All();
            $virtualDirectories = array();

            $virtualDirectories[0] = $this->Html->SafeHtml('<root>');
            foreach($dbVirtualDirectories as $dbVirtualDirectory){
                $virtualDirectories[$dbVirtualDirectory->Id] = $dbVirtualDirectory->GetFullPath();
            }

            $this->Set('VirtualDirectories', $virtualDirectories);
            $this->Set('VirtualDirectory', $virtualDirectory);
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

    public function CanEditDirectory($id)
    {
        if($this->IsLoggedIn()) {
            return true;
        }

        return false;
    }

    public function CanEditDocument($id)
    {
        if($this->IsLoggedIn()) {
            return true;
        }

        return false;
    }

    private function GetNode($path)
    {
        $directoryResult = $this->GetVirtualDirectory($path, $this->GetCurrentUser());
        $virtualDirectory = $directoryResult['Directory'];
        $this->SetBreadCrumbs($directoryResult['Path']);

        if(!$virtualDirectory == null && is_a($virtualDirectory, 'VirtualDirectory')){
            return array(
                'Node' => $virtualDirectory,
                'Path' => $directoryResult['Path']
            );
        }

        if($virtualDirectory == null){
            $document = $this->GetDocument($path, $this->GetCurrentUser());
            return array(
                'Node' => $document,
                'Path' => $directoryResult['Path']
            );
        }

        return array(
            'Node' => null,
            'Path' => $directoryResult['Path']
        );
    }

    private function GetVirtualDirectory($path, $currentUser)
    {
        if(!is_array($path)){
            return null;
        }

        $directories = $this->Models->VirtualDirectory->Where(array('ParentDirectoryId' => null));
        $directory = null;

        $directoryList = array();

        foreach($path as $name){
            $directory = $directories->Where(array('Name' => $name))->First();
            if($directory == null){
                return null;
            }

            $directoryList[] = $directory;

            // Check user privileges for every directory in the file hierarchy
            if(!$this->CheckUserPrivileges($directory, $currentUser)) {
                return null;
            }

            $directories = $directory->VirtualDirectories->Where(array('ParentDirectoryId' => $directory->Id));
        }

        return array(
            'Directory' => $directory,
            'Path' => $directoryList
        );
    }

    private function GetDocument($path, $currentUser)
    {
        if(!is_array($path)){
            return null;
        }

        // Strip away the last entry
        $fileName = end($path);
        array_pop($path);

		$response = $this->GetVirtualDirectory($path, $currentUser);
        $parentDirectory = $response['Directory'];
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

    protected  function IsImageFile($fileName)
    {
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        $allowedFileExtensions = array(
            'jpg',
            'png',
            'bmp',
            'gif'
        );

        return in_array($fileExtension, $allowedFileExtensions);
    }
}