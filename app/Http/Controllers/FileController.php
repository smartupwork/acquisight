<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deal;
use Illuminate\Support\Facades\File;
use App\Services\SizeService;
use App\Services\GcsStorageService;
use App\Models\DealFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class FileController extends Controller
{

    public function store(Request $request, GcsStorageService $gcsStorageService)
    {
        $request->validate([
            'deal_id' => 'required|exists:deals,id',
            'folder_id' => 'required|exists:deal_folders,id',
            'drive_folder_id' => 'required|string',
            'files.*' => 'required|file|max:2048',
        ]);

        $uploadedFiles = $request->file('files');
        $gcsFolderPath = $request->drive_folder_id;

        if (empty($gcsFolderPath)) {
            return redirect()->back()->with('error', 'GCS folder path is missing.');
        }

        $uploadedPaths = [];

        foreach ($uploadedFiles as $file) {
            $uploadedFilePath = $gcsStorageService->uploadFile($gcsFolderPath, $file);

            if ($uploadedFilePath) {
                DealFile::create([
                    'deal_id' => $request->deal_id,
                    'folder_id' => $request->folder_id,
                    'user_id' => Auth::id(),
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $uploadedFilePath,
                ]);

                $uploadedPaths[] = $uploadedFilePath;
            }
        }

        if (count($uploadedPaths) > 0) {
            return redirect()->back()->with('success', 'Files uploaded successfully!');
        } else {
            return redirect()->back()->with('error', 'No files were uploaded.');
        }
    }


    // public function viewFolderFiles($id)
    // {
    //     $files = DealFile::where('folder_id', $id)->get();
    //     return view('backend.files.index', compact('files', 'id'));
    // }

    // public function viewFile($id, GcsStorageService $gcsStorageService)
    // {
    //     $file = DealFile::findOrFail($id);
    //     $signedUrl = $gcsStorageService->getSignedUrl($file->file_path);

    //     $mimeType = Storage::disk('gcs')->mimeType($file->file_path);

    //     return response()->json([
    //         'url' => $signedUrl,
    //         'type' => $mimeType
    //     ]);
    // }

    public function viewFolderFiles($id, GcsStorageService $gcsStorageService)
    {
        $files = DealFile::where('folder_id', $id)->get();

        // Append signed URL and MIME type to each file
        $files->transform(function ($file) use ($gcsStorageService) {
            $file->signed_url = $gcsStorageService->getSignedUrl($file->file_path);
            $file->mime_type = Storage::disk('gcs')->mimeType($file->file_path);
            return $file;
        });

        return view('backend.files.index', compact('files', 'id'));
    }
}
