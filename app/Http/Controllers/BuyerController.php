<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deal;
use App\Models\DealMeta;
use App\Models\DealFolder;
use App\Models\DealFile;
use Illuminate\Support\Facades\Storage;
use App\Services\GcsStorageService;

class BuyerController extends Controller
{
    public function deals_detail($id)
    {
        $deal = Deal::findOrFail($id);
        $dealMeta = DealMeta::where('deal_id', $id)->first();

        return view('backend.buyer.detail', [
            'deal' => $deal,
            'dealMeta' => $dealMeta,
        ]);
    }

    public function viewDeal($id)
    {
        $deal = Deal::findOrFail($id);
        $foldersData = DealFolder::where('deal_id', $id)->get();

        return view('backend.buyer.folder-list', [
            'deal' => $deal,
            'folders' => $foldersData,
        ]);
    }

    public function viewFolderFiles($id, GcsStorageService $gcsStorageService)
    {
        $files = DealFile::where('folder_id', $id)->get();

        $files->transform(function ($file) use ($gcsStorageService) {
            $file->signed_url = $gcsStorageService->getSignedUrl($file->file_path);
            $file->mime_type = Storage::disk('gcs')->mimeType($file->file_path);
            return $file;
        });

        return view('backend.buyer.files-list', compact('files', 'id'));
    }
}
