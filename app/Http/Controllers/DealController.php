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
use Google\Client;
use App\Services\GcsStorageService;
use App\Mail\AlertInviteMail;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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



    public function store(Request $request, GcsStorageService $gcsStorageService)
    {
        $request->validate([
            'name' => 'required|string',
            'status' => 'required',
        ]);

        DB::beginTransaction();

        try {
            
            $deal = Deal::create([
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->status,
                'user_id' => Auth::id(),
            ]);

            
            $dealFolderPrefix = $gcsStorageService->createDealFolder($deal->name);
            $deal->update(['gcs_deal_id' => $dealFolderPrefix]);

           
            $subfolders = [
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
                'Misc',
            ];

            // Create subfolders in GCS
            $subfolderPrefixes = $gcsStorageService->createSubfolders($dealFolderPrefix, $subfolders);

            // Store subfolder records in the database
            foreach ($subfolderPrefixes as $subfolderName => $subfolderPrefix) {
                DealFolder::create([
                    'deal_id' => $deal->id,
                    'folder_name' => $subfolderName,
                    'gcs_folder_id' => $subfolderPrefix,
                ]);
            }

            DB::commit();

            return redirect()->route('deals.index')->with('success', 'Deal and folders created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create deal and folders: ' . $e->getMessage());
        }
    }



    public function showInviteContactForm($dealId)
    {

        $deal = Deal::findOrFail($dealId);
        return view('backend.deals.invite', compact('deal'));
    }

    public function sendInvite(Request $request, $dealId)
    {

        $user = User::where('id', Auth::id())->first();

        $invite_sender_email = $user->email;

        $seller_email = $request->email;

        $deal = Deal::findOrFail($dealId);

        $token = Str::random(40);

        DealInvitation::create([
            'deal_id' => $deal->id,
            'email' => $seller_email,
            'token' => $token,
            'accepted' => 0
        ]);

        $link = route('seller.register', ['token' => $token]);

        Mail::to($seller_email)->send(new DealInvitationMail($deal, $link));

        Mail::to($invite_sender_email)->send(new AlertInviteMail($deal, $link, $seller_email));

        return redirect()->route('deals.index')->with('success', 'Invitation sent successfully!');
    }




    public function viewDeal($id)
    {
        $deal = Deal::findOrFail($id);
        $foldersData = DealFolder::where('deal_id', $id)->get();

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

    public function edit($id)
    {
        $deal = Deal::findOrFail($id);

        return response()->json([
            'id' => $deal->id,
            'name' => $deal->name,
            'description' => $deal->description,
            'status' => $deal->status, // Ensure this is either 0 or 1
        ]);
    }

    public function update(Request $request)
    {

        $id = $request->deal_id;

        $deal = Deal::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required',
        ]);

        $deal->update($request->all());

        return redirect()->route('deals.index')->with('success', 'Deal Updated successfully!');
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
