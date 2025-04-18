<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\DealInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\DealFolder;
use App\Models\DealFile;
use App\Services\GcsStorageService;
use Illuminate\Support\Facades\Storage;
use App\Models\DealMeta;

class SellerController extends Controller
{
    public function index()
    {
        $loggedInUserEmail = Auth::user()->email;

        $deals = DB::table('deals')
            ->join('deal_invitations', 'deals.id', '=', 'deal_invitations.deal_id')
            ->select(
                'deals.id as id',
                'deals.name as deal_name',
                'deals.status as deal_status',
                'deals.created_at as deal_created_at',
                'deals.description as deal_description',
                'deals.gcs_deal_id as drive_deal_id'
            )
            ->where('deal_invitations.email', $loggedInUserEmail)
            ->groupBy('deals.id', 'deals.name', 'deals.status', 'deals.created_at', 'deals.description', 'deals.gcs_deal_id') // Grouping by deal fields
            ->get();

        return view('backend.seller.deal-list', ['deals' => $deals]);
    }

    public function deals_detail($id)
    {
        $deal = Deal::findOrFail($id);
        $dealMeta = DealMeta::where('deal_id', $id)->first();

        return view('backend.seller.detail', [
            'deal' => $deal,
            'dealMeta' => $dealMeta,
        ]);
    }


    public function viewDeal($id)
    {
        $deal = Deal::findOrFail($id);
        $foldersData = DealFolder::where('deal_id', $id)->get();

        return view('backend.seller.folder-list', [
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

        return view('backend.seller.files-list', compact('files', 'id'));
    }
}
