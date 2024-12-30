<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\DealInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SellerController extends Controller
{
    public function index()
    {

        // get deals for authenticated user

        $loggedInUserEmail = Auth::user()->email;

        $deals = DB::table('deals')
            ->join('deal_invitations', 'deals.id', '=', 'deal_invitations.deal_id')
            ->select('deals.name as deal_name', 'deals.status as deal_status', 'deals.created_at as deal_created_at', 'deals.description as deal_description')
            ->where('deal_invitations.email', $loggedInUserEmail)
            ->get();

        return view('backend.seller.deal-list', ['deals' => $deals]);
    }
}
