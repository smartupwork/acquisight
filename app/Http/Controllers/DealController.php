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
use App\Mail\InformAccessMail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\DealMeta;

class DealController extends Controller
{
    public function index()
    {
        $deals = Deal::with('folders')->get();
        return view('backend.deals.index')->with('deals', $deals);
    }


    public function deal_details($deal_id)
    {

        $deal = Deal::findOrFail($deal_id);
        $dealMeta = DealMeta::where('deal_id', $deal_id)->first();

        return view('backend.deals.detail', [
            'deal' => $deal,
            'dealMeta' => $dealMeta,
        ]);
    }


    public function create()
    {
        $brokers = User::where('roles_id', 2)->get();
        return view('backend.deals.create', ['brokers' => $brokers]);
    }



    public function store(Request $request, GcsStorageService $gcsStorageService)
    {
        $request->validate([
            'name' => 'required|string',
            'status' => 'required',
            'deal_image' => 'nullable|max:102400',
        ]);

        DB::beginTransaction();

        try {

            $imagePath = null;
            if ($request->hasFile('deal_image')) {
                $image = $request->file('deal_image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = 'assets/images/deal/' . $imageName;
                $image->move(public_path('assets/images/deal'), $imageName);
            }

            $deal = Deal::create([
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->status,
                'user_id' => Auth::id(),
                'deal_image' => $imagePath,
            ]);

           $deal_invitation =  DealInvitation::create([
                'deal_id' => $deal->id,
                'email' => $request->broker_email,
                'token' => Null,
                'accepted' => 1,
                'user_type' => 2
            ]);

            $deals_meta = DealMeta::create([
                'deal_id' => $deal->id,
                'asking_price' => $request->asking_price,
                'gross_revenue' => $request->gross_revenue,
                'cash_flow' => $request->cash_flow,
                'ebitda' => $request->ebitda,
                'inventory' => $request->inventory,
                'ffe' => $request->ffe,
                'business_desc' => $request->business_desc,
                'location' => $request->location,
                'no_employee' => $request->no_employee,
                'real_estate' => $request->real_estate,
                'rent' => $request->rent,
                'lease_expiration' => $request->lease_expiration,
                'facilities' => $request->facilities,
                'market_outlook' => $request->market_outlook,
                'selling_reason' => $request->selling_reason,
                'train_support' => $request->train_support,
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
                'Confidential Reviews',
                'Misc',
            ];


            $subfolderPrefixes = $gcsStorageService->createSubfolders($dealFolderPrefix, $subfolders);

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

        $existingUser = User::where('email', $seller_email)->first();

        $existingInvitation = DealInvitation::where('email', $seller_email)
            ->where('deal_id', $deal->id)
            ->first();

        if ($existingUser || $existingInvitation) {
            DealInvitation::create([
                'deal_id' => $deal->id,
                'email' => $seller_email,
                'token' => NULL,
                'accepted' => 1,
                'user_type' => $existingUser->roles_id
            ]);

            Mail::to($seller_email)->send(new InformAccessMail($deal));
            return redirect()->route('deals.index')->with('success', 'Invitation sent successfully!');
        }

        $token = Str::random(40);

        $user_type = $request->roles_id;

        DealInvitation::create([
            'deal_id' => $deal->id,
            'email' => $seller_email,
            'token' => $token,
            'accepted' => 0,
            'user_type' => $user_type
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

        $deal_meta = DealMeta::where('deal_id', $id)->first();

        return response()->json([

            'id' => $deal->id,
            'name' => $deal->name,
            'description' => $deal->description,
            'status' => $deal->status,
            'deal_meta_id' => $deal_meta->id,
            'asking_price' => $deal_meta->asking_price,
            'gross_revenue' => $deal_meta->gross_revenue,
            'cash_flow' => $deal_meta->cash_flow,
            'ebitda' => $deal_meta->ebitda,
            'inventory' => $deal_meta->inventory,
            'ffe' => $deal_meta->ffe,
            'business_desc' => $deal_meta->business_desc,
            'location' => $deal_meta->location,
            'no_employee' => $deal_meta->no_employee,
            'real_estate' => $deal_meta->real_estate,
            'rent' => $deal_meta->rent,
            'lease_expiration' => $deal_meta->lease_expiration,
            'facilities' => $deal_meta->facilities,
            'market_outlook' => $deal_meta->market_outlook,
            'selling_reason' => $deal_meta->selling_reason,
            'train_support' => $deal_meta->train_support,

        ]);
    }

    public function update(Request $request)
    {
        $id = $request->deal_id;
        $deal_meta_id = $request->deal_meta_id;


        $deal = Deal::findOrFail($id);

        if ($request->hasFile('deal_image')) {

            if ($deal->deal_image && File::exists(public_path($deal->deal_image))) {
                File::delete(public_path($deal->deal_image));
            }


            $image = $request->file('deal_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = 'assets/images/deal/' . $imageName;
            $image->move(public_path('assets/images/deal'), $imageName);
            $deal->deal_image = $imagePath;
        }


        $deal->update([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'deal_image' => $deal->deal_image,
        ]);

        $dealMeta = DealMeta::findOrFail($deal_meta_id);


        $metaData = $request->except(['_token', '_method', 'deal_id', 'deal_meta_id', 'name', 'description', 'status']);

        $dealMeta->update($metaData);

        return redirect()->back()->with('success', 'Deal updated successfully!');
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
