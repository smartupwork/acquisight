<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deal;

class DealSettingController extends Controller
{
    public function index()
    {
        $deals = Deal::all();
        return view('backend.setting.index', ['deals' => $deals]);
    }

    public function update_list_type(Request $request, Deal $deal)
    {
        $request->validate([
            'listing_type' => 'required|in:public,private',
        ]);

        $deal->listing_type = $request->listing_type;
        $deal->save();

        return redirect()->back()->with('success', 'Deal settings updated successfully.');
    }
}
