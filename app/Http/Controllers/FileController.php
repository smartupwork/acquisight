<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deal;
use Illuminate\Support\Facades\File;
use App\Services\SizeService;
use App\Services\GoogleDriveService;
use App\Models\DealFile;
use Illuminate\Support\Facades\Auth;


class FileController extends Controller
{

    public function store(Request $request, GoogleDriveService $googleDriveService)
    {
        $request->validate([
            'deal_id' => 'required|exists:deals,id',
            'folder_name' => 'required|string',
            'folder_id' => 'required',
            'files.*' => 'required|file|max:2048', // Validate each file
        ]);

        $uploadedFiles = $request->file('files');

        foreach ($uploadedFiles as $file) {
            $fileName = $file->getClientOriginalName();

            $fileMetadata = new \Google\Service\Drive\DriveFile([
                'name' => $fileName,
                'parents' => [$request->drive_folder_id],
            ]);

            $driveFile = $googleDriveService->uploadFile($fileMetadata, $file);

            DealFile::create([
                'deal_id' => $request->deal_id,
                'folder_id' => $request->folder_id,
                'user_id' => Auth::id(),
                'file_name' => $fileName,
                'file_path' => $driveFile->webViewLink, // Google Drive file link
            ]);
        }

        return redirect()->back()->with('success', 'Files uploaded successfully!');
    }


    public function viewFolderFiles($id)
    {
        $files = DealFile::where('folder_id', $id)->get();

        return view('backend.files.index', compact('files', 'id'));
    }
}
