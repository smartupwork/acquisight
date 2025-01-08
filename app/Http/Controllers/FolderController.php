<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FolderController extends Controller
{


    public function store(Request $request)
    {

        $request->validate([
            'file' => 'required|file|max:10240', // Max file size: 10MB
            'deal_name' => 'required|string',
            'deal_id' => 'required|integer',
            'folder_name' => 'required|string',
        ]);
    
        // Extract input data
        $dealName = $request->input('deal_name');
        $dealId = $request->input('deal_id');
        $folderName = $request->input('folder_name');
        $file = $request->file('file');
    
        // Construct the path where the file will be stored
        $folderPath = public_path("deals_main/{$dealName}_{$dealId}/{$folderName}");
    
        // Check if the folder exists
        if (!file_exists($folderPath)) {
            return back()->with('error', 'The specified folder does not exist.');
        }
    
        // Move the uploaded file to the existing folder
        $fileName = $file->getClientOriginalName(); // Preserve the original file name
        $file->move($folderPath, $fileName);
    
        // Return success response
        return back()->with('success', 'File uploaded successfully to folder: ' . $folderName);
    }
}
