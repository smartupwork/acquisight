<?php

namespace App\Services;

use Google\Cloud\Storage\StorageClient;
use Google\Cloud\Iam\V1\Policy;
use Google\Cloud\Iam\V1\Binding;
use Google\Type\Expr;
use Illuminate\Support\Facades\Log;

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


            $this->bucket->upload(
                '',
                ['name' => $subfolderPrefix]
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
            $signedUrl = $object->signedUrl(new \DateTime('tomorrow')); // 24 hours expiry

            // Log the signed URL to verify
            \Log::info("Generated Signed URL for {$filePath}: {$signedUrl}");

            return $signedUrl;
        } catch (\Exception $e) {
            \Log::error('GCS Signed URL Error: ' . $e->getMessage());
            return null;
        }
    }

    public function deleteFile($filePath)
    {
        try {
            Log::info("Attempting to delete file: " . $filePath);

            $object = $this->bucket->object($filePath);
            if (!$object->exists()) {
                Log::warning("File does not exist in GCS: " . $filePath);
                return false;
            }

            $object->delete();
            Log::info("File deleted successfully from GCS: " . $filePath);
            return true;
        } catch (\Exception $e) {
            Log::error("Error deleting file from GCS: " . $e->getMessage(), [
                'filePath' => $filePath,
                'exception' => $e
            ]);
            return false;
        }
    }
}
