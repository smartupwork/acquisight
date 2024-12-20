<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\DealFolder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

         foreach ($defaultFolders as $folder) {
            $save = DealFolder::create(['deal_id' => $deal->id, 'folder_name' => $folder]);
        }

        if ($save) {
            return redirect()->route('deals.index')->with('success', 'Deal Created successfully!');
        } else {
            return redirect()->route('deals.index')->with('success', 'Sorry, something went wrong.');
        }
    }

    public function delete($id){
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
