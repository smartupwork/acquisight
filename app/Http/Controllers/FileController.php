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
            'file' => 'required|file|max:2048',
        ]);

        $file = $request->file('file');
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

        return redirect()->back()->with('success', 'File uploaded successfully!');
    }


    public function viewFolderFiles($id)
    {
        $files = DealFile::where('folder_id', $id)->get();

        return view('backend.files.index', compact('files', 'id'));
    }

    // public function viewFolderFiles($id, $folderName, SizeService $sizeService)
    // {
    //     $deal = Deal::findOrFail($id);

    //     $folderPath = public_path("deals_main/{$deal->name}_{$deal->id}/{$folderName}");

    //     if (!File::exists($folderPath) || !File::isDirectory($folderPath)) {
    //         abort(404, "Folder not found.");
    //     }

    //     $files = File::files($folderPath);

    //     $filesData = [];
    //     foreach ($files as $file) {
    //         $filesData[] = [
    //             'name' => $file->getFilename(),
    //             'size' => $sizeService->formatSizeUnits($file->getSize()),
    //             'last_modified' => \Carbon\Carbon::createFromTimestamp($file->getMTime())->toDateTimeString(),
    //         ];
    //     }

    //     return view('backend.files.index', [
    //         'deal' => $deal,
    //         'folderName' => $folderName,
    //         'files' => $filesData,
    //     ]);
    // }
}
