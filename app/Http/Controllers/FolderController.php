<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GcsStorageService;
use App\Models\DealFolder;
use Exception;

class FolderController extends Controller
{


    public function store(Request $request)
    {

        $request->validate([
            'file' => 'required|file|max:10240', // Max file size: 10MB
            'deal_name' => 'required|string',
            'deal_id' => 'required|integer',
            'folder_name' => 'required|string',
        ]);

        // Extract input data
        $dealName = $request->input('deal_name');
        $dealId = $request->input('deal_id');
        $folderName = $request->input('folder_name');
        $file = $request->file('file');

        $folderPath = public_path("deals_main/{$dealName}_{$dealId}/{$folderName}");

        // Check if the folder exists
        if (!file_exists($folderPath)) {
            return back()->with('error', 'The specified folder does not exist.');
        }

        // Move the uploaded file to the existing folder
        $fileName = $file->getClientOriginalName(); // Preserve the original file name
        $file->move($folderPath, $fileName);

        // Return success response
        return back()->with('success', 'File uploaded successfully to folder: ' . $folderName);
    }

    public function new_folder_store(Request $request, GcsStorageService $gcsStorageService)
    {

        $request->validate([
            'gcs_deal_id' => 'required|string',
            'deal_id' => 'required|integer',
            'folder_name' => 'required|string',
        ]);

        $result = $gcsStorageService->newFolder(
            $request->deal_id,
            $request->gcs_deal_id,
            $request->folder_name
        );

        if ($result) {
            return redirect()->back()->with('success', 'Folder Created Successfully.');
        }

        return redirect()->back()->with('error', 'Sorry something went wrong.');
    }

    public function deleteFolder(Request $request, GCSStorageService $gcsStorageService)
    {
        $folderId = $request->folder_id;
        $folderPath = $request->folder_path;

        $gcs_result = $gcsStorageService->deleteFolder($folderPath);

        if (!$gcs_result) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, something went wrong while deleting the folder from GCS.'
            ]);
        }

        $result = DealFolder::where('id', $folderId)->delete();

        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Folder deleted successfully!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Sorry, something went wrong while deleting from the database.'
        ]);
    }
}
