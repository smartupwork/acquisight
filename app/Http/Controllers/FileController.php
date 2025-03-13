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
use App\Models\FileViewLog;


class FileController extends Controller
{

    public function store(Request $request, GcsStorageService $gcsStorageService)
    {

        $request->validate([
            'deal_id' => 'required|exists:deals,id',
            'folder_id' => 'required|exists:deal_folders,id',
            'drive_folder_id' => 'required|string',
            'files.*' => 'required|file|max:25600',
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

    public function viewFolderFiles($id, GcsStorageService $gcsStorageService)
    {
        $files = DealFile::where('folder_id', $id)->get();

        $files->transform(function ($file) use ($gcsStorageService) {
            $file->signed_url = $gcsStorageService->getSignedUrl($file->file_path);
            $file->mime_type = Storage::disk('gcs')->mimeType($file->file_path);
            return $file;
        });


        return view('backend.files.index', compact('files', 'id'));
    }

    public function logFileView(Request $request)
    {
        $request->validate([
            'file_id' => 'required|integer',
            'file_name' => 'required|string',
        ]);

        FileViewLog::create([
            'user_id' => Auth::id(),
            'file_id' => $request->file_id,
            'file_name' => $request->file_name,
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
            'viewed_at' => now(),
        ]);

        return response()->json(['message' => 'File view logged successfully']);
    }

    public function deleteFile(Request $request, GcsStorageService $gcsStorageService)
    {



        $file = DealFile::find($request->file_id);

        if (!$file) {
            return response()->json(['success' => false, 'message' => 'File not found!']);
        }

        $deleteFromGCS = $gcsStorageService->deleteFile($request->file_path);

        if (!$deleteFromGCS) {
            return response()->json(['success' => false, 'message' => 'Error deleting file from GCS!']);
        }

        $file->delete();
        
        if ($file) {
            return response()->json(['success' => true, 'message' => 'File deleted successfully!']);
        }

        return response()->json(['success' => false, 'message' => 'Error deleting file!']);
    }
}
