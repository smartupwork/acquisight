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
use Illuminate\Support\Facades\File;
use Illuminate\Support\Carbon;


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

        // send invite and deal creation

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




    public function viewDeal($id)
    {
        $deal = Deal::findOrFail($id);
        $mainFolderPath = public_path("deals_main/{$deal->name}_{$deal->id}");

        $foldersData = [];

        if (File::exists($mainFolderPath)) {
            $folders = File::directories($mainFolderPath);

            foreach ($folders as $folder) {
                $foldersData[] = [
                    'name' => basename($folder),
                    'last_modified' => Carbon::createFromTimestamp(File::lastModified($folder))->toDateTimeString(),
                    'size' => $this->getFolderSize($folder),
                ];
            }
        }

        return view('backend.deals.view', [
            'deal' => $deal,
            'folders' => $foldersData,
        ]);
    }

    private function getFolderSize($folderPath)
    {
        $size = 0;

        foreach (File::allFiles($folderPath) as $file) {
            $size += $file->getSize();
        }

        return $this->formatSizeUnits($size);
    }

    private function formatSizeUnits($size)
    {
        if ($size >= 1073741824) {
            return number_format($size / 1073741824, 2) . ' GB';
        } elseif ($size >= 1048576) {
            return number_format($size / 1048576, 2) . ' MB';
        } elseif ($size >= 1024) {
            return number_format($size / 1024, 2) . ' KB';
        } elseif ($size > 1) {
            return $size . ' bytes';
        } elseif ($size == 1) {
            return $size . ' byte';
        } else {
            return '0 bytes';
        }
    }

    public function viewFolderFiles($id, $folderName)
    {
        $deal = Deal::findOrFail($id); // Ensure the deal exists

        $folderPath = public_path("deals_main/{$deal->name}_{$deal->id}/{$folderName}");

        if (!File::exists($folderPath) || !File::isDirectory($folderPath)) {
            abort(404, "Folder not found.");
        }

        // Get files in the folder
        $files = File::files($folderPath);

        $filesData = [];
        foreach ($files as $file) {
            $filesData[] = [
                'name' => $file->getFilename(),
                'size' => $this->formatSizeUnits($file->getSize()),
                'last_modified' => \Carbon\Carbon::createFromTimestamp($file->getMTime())->toDateTimeString(),
            ];
        }

        return view('backend.seller.view-folder-files', [
            'deal' => $deal,
            'folderName' => $folderName,
            'files' => $filesData,
        ]);
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
