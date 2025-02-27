<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Google\Cloud\Storage\StorageClient;
use App\Models\FileViewLog;
use Illuminate\Support\Facades\DB;
use App\Models\Deal;
use Carbon\Carbon;
use App\Models\DealFile;

class DashboardController extends Controller
{
    public function showAdminDashboard()
    {
        $today = Carbon::today(); // Get today's date

        // Total count
        $totalUsers = User::count();
        $totalDeals = Deal::count();
        $totalFile = DealFile::count();

        // Today's count
        $todayUsers = User::whereDate('created_at', $today)->count();
        $todayDeals = Deal::whereDate('created_at', $today)->count();
        $todayFile = DealFile::whereDate('created_at', $today)->count();

        return view('backend.admin.dashboard', compact(
            'totalUsers',
            'totalDeals',
            'totalFile',
            'todayUsers',
            'todayDeals',
            'todayFile'
        ));
    }


    public function showUserDashboard()
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


    public function uploadFile()
    {
        return view('welcome');
    }

    public function view_logs()
    {
        $files = FileViewLog::with([
            'user:id,name,email',
            'dealFile:id,file_name,deal_id,folder_id',
            'dealFile.deal:id,gcs_deal_id',
            'dealFile.dealFolder:id,folder_name'
        ])
            ->latest()
            ->paginate(10);

        return view('backend.logs.index', compact('files'));
    }


    public function submit(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // Max size 10MB
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = Str::random(25) . '.' . $file->getClientOriginalExtension();

            try {

                $storage = new StorageClient([
                    'keyFilePath' => base_path('acquisight-a82ce8b80000.json'), // Path to your key file
                ]);

                // Get the bucket
                $bucket = $storage->bucket('acqfiles'); // Replace with your bucket name

                // Upload the file to the bucket
                $bucket->upload(
                    fopen($file->getRealPath(), 'r'), // Open file for reading
                    [
                        'name' => $fileName // Set the file name in the bucket
                    ]
                );

                // Return success response
                return response()->json([
                    'message' => 'File uploaded successfully',
                    'file_url' => 'https://storage.googleapis.com/acqfiles/' . $fileName, // URL to access the file
                ]);
            } catch (\Exception $e) {
                Log::error('Upload failed:', ['error' => $e->getMessage()]);
                return response()->json(['message' => 'Upload failed'], 500);
            }
        }

        return response()->json(['message' => 'No file uploaded'], 400);
    }
}
