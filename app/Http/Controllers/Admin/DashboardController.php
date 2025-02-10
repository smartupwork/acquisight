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


class DashboardController extends Controller
{
    public function showAdminDashboard()
    {
        return view('backend.admin.dashboard');
    }

    public function showUserDashboard()
    {
        $users = Auth::user();
        return view('backend.user.dashboard', compact('users'));
    }


    public function uploadFile()
    {
        return view('welcome');
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
