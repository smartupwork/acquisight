<?php

namespace App\Services;

use Google\Cloud\Storage\StorageClient;
use Illuminate\Support\Facades\Storage;

class GcsStorageService
{
    protected $storage;
    protected $bucket;

    public function __construct()
    {

        $this->storage = new StorageClient([
            'keyFilePath' => base_path('acquisight-a82ce8b80000.json')
        ]);


        $this->bucket = $this->storage->bucket('acqfiles');
    }


    public function createDealFolder($dealName)
    {
        $uniqueDealName = $dealName . '-' . uniqid(); // Ensure uniqueness
        $parentFolderPrefix = $uniqueDealName . '/';

        $this->bucket->upload(
            '',
            ['name' => $parentFolderPrefix]
        );

        return $parentFolderPrefix;
    }

    public function createSubfolders($dealFolderPrefix, $subfolders)
    {
        $subfolderPrefixes = [];

        foreach ($subfolders as $subfolderName) {
            $subfolderPrefix = $dealFolderPrefix . $subfolderName . '/';

            // Upload an empty file to create the subfolder in GCS
            $this->bucket->upload(
                '', // Empty file contents
                ['name' => $subfolderPrefix] // Creates the subfolder
            );

            $subfolderPrefixes[$subfolderName] = $subfolderPrefix;
        }

        return $subfolderPrefixes;
    }

    public function uploadFile($gcsFolderPath, $file)
    {
        $fileName = time() . '-' . $file->getClientOriginalName();

        // Ensure the folder path is correctly formatted
        $gcsFolderPath = trim($gcsFolderPath, '/');

        if (empty($gcsFolderPath)) {
            \Log::error('GCS Upload Error: Folder path is empty.');
            return null;
        }

        $filePath = $gcsFolderPath . '/' . $fileName;

        try {
            $this->bucket->upload(
                fopen($file->getRealPath(), 'r'),
                [
                    'name' => $filePath,
                ]
            );

            return $filePath;
        } catch (\Exception $e) {
            \Log::error('GCS File Upload Error: ' . $e->getMessage());
            return null;
        }
    }

    public function getSignedUrl($filePath, $expiration = 60)
    {
        if (!$filePath) {
            return null;
        }

        try {
            $object = $this->bucket->object($filePath);
            return $object->signedUrl(new \DateTime('tomorrow')); // URL expires in 24 hours
        } catch (\Exception $e) {
            \Log::error('GCS Signed URL Error: ' . $e->getMessage());
            return null;
        }
    }
}
