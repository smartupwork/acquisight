<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\DealFolder;
use App\Models\DealInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\DealInvitationMail;

class DealController extends Controller
{
    public function index()
    {
        $deals = Deal::with('folders')->get();
        return view('backend.deals.index')->with('deals', $deals);
    }


    public function create()
    {
        return view('backend.deals.create');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string',
                'status' => 'required'
            ]
        );

        $deal = Deal::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'user_id' => Auth::id(),
        ]);

        $defaultFolders = [
            'Corporate Documentation',
            'Tax Returns',
            'Financial Statements',
            'Bank Statements',
            'Real Estate and Leases',
            'Assets',
            'Litigation',
            'HR and Employees',
            'Products and Services',
            'Licenses',
            'Misc'
        ];

        // Ensure the main directory 'deals_main' exists
        $dealsMainPath = public_path('deals_main');
        if (!file_exists($dealsMainPath)) {
            mkdir($dealsMainPath, 0777, true);
        }

        // Create the main folder inside 'deals_main'
        $mainFolderPath = $dealsMainPath . "/{$deal->name}_{$deal->id}";
        if (!file_exists($mainFolderPath)) {
            mkdir($mainFolderPath, 0777, true);
        }

        foreach ($defaultFolders as $folder) {
            // Create subfolders inside the main folder
            $subFolderPath = $mainFolderPath . '/' . $folder;

            if (!file_exists($subFolderPath)) {
                mkdir($subFolderPath, 0777, true);
            }

            // Save each folder record to the database
            DealFolder::create(['deal_id' => $deal->id, 'folder_name' => $folder]);
        }

        if ($deal) {
            return redirect()->route('deals.index')->with('success', 'Deal Created successfully!');
        } else {
            return redirect()->route('deals.index')->with('error', 'Sorry, something went wrong.');
        }
    }


    public function showInviteContactForm($dealId)
    {

        $deal = Deal::findOrFail($dealId);
        return view('backend.deals.invite', compact('deal'));
    }

    public function sendInvite(Request $request, $dealId)
    {
      
        // $request->validate(['email' => 'required|email']);

        $deal = Deal::findOrFail($dealId);
      
        $token = Str::random(40);
      
        DealInvitation::create([
            'deal_id' => $deal->id,
            'email' => $request->email,
            'token' => $token,
            'accepted' => 0
        ]);

        $link = route('seller.register', ['token' => $token]);

        Mail::to($request->email)->send(new DealInvitationMail($deal, $link));

        return redirect()->route('deals.index')->with('success', 'Invitation sent successfully!');
    }


    public function delete($id)
    {
        $delete = Deal::findorFail($id);
        $status = $delete->delete();
        if ($status) {
            request()->session('success', 'User Successfully deleted');
        } else {
            request()->session('error', 'There is an error while deleting users');
        }
        return redirect()->route('deals.index');
    }
}
