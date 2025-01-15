<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Google\Service\Drive\Permission;

class GoogleDriveService
{
    protected $client;
    protected $driveService;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setClientId(config('services.google.client_id'));
        $this->client->setClientSecret(config('services.google.client_secret'));
        $this->client->refreshToken(config('services.google.refresh_token'));

        $this->driveService = new Drive($this->client);
    }



    public function createDealFolder($dealName, $dealId)
    {
        $folderMetadata = new DriveFile([
            'name' => $dealName . '_' . $dealId,
            'mimeType' => 'application/vnd.google-apps.folder',
        ]);

        $folder = $this->driveService->files->create($folderMetadata, ['fields' => 'id']);
        return $folder->id;
    }


    public function createSubfolder($subfolderName, $parentFolderId)
    {
        $subfolderMetadata = new DriveFile([
            'name' => $subfolderName,
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents' => [$parentFolderId],
        ]);

        $subfolder = $this->driveService->files->create($subfolderMetadata, ['fields' => 'id']);
        return $subfolder->id;
    }

    public function uploadFile($fileMetadata, $file)
    {
        $fileContent = file_get_contents($file->getRealPath()); // Get the file content

        $driveFile = $this->driveService->files->create($fileMetadata, [
            'data' => $fileContent,
            'mimeType' => $file->getMimeType(),
            'uploadType' => 'multipart',
            'fields' => 'id, webViewLink',
        ]);

        return $driveFile;
    }


    /**
     * Share Google Drive Folder with a User
     *
     * @param string $folderId
     * @param string $email
     * @param string $role (owner, writer, reader)
     * @return bool
     */
    public function shareGoogleDriveFolder($folderId, $email)
    {
        try {
            
            $permission = new \Google\Service\Drive\Permission();
            $permission->setType('user');
            $permission->setEmailAddress($email);

            
            $permission->setRole('reader');
            $this->driveService->permissions->create($folderId, $permission, [
                'sendNotificationEmail' => true,  
            ]);

           
            $permission->setRole('writer');
            $this->driveService->permissions->create($folderId, $permission, [
                'sendNotificationEmail' => true,
            ]);
        } catch (\Exception $e) {
           
            \Log::error('Error granting permissions: ' . $e->getMessage());
            throw new \Exception('Failed to grant Google Drive permissions.');
        }
    }
}
