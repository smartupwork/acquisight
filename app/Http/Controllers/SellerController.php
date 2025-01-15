<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\DealInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\DealFolder;
use App\Models\DealFile;

class SellerController extends Controller
{
    public function index()
    {

        $loggedInUserEmail = Auth::user()->email;

        // Fetch deals associated with the authenticated user and include drive_deal_id
        $deals = DB::table('deals')
            ->join('deal_invitations', 'deals.id', '=', 'deal_invitations.deal_id')
            ->select(
                'deals.id as id',
                'deals.name as deal_name',
                'deals.status as deal_status',
                'deals.created_at as deal_created_at',
                'deals.description as deal_description',
                'deals.drive_deal_id as drive_deal_id'
            ) // Include drive_deal_id
            ->where('deal_invitations.email', $loggedInUserEmail)
            ->get();

        return view('backend.seller.deal-list', ['deals' => $deals]);
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

    public function viewFolderFiles($id)
    {
        $files = DealFile::where('folder_id', $id)->get();

        return view('backend.seller.files-list', compact('files', 'id'));
    }
}
