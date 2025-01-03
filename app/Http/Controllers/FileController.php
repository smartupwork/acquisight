<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deal;
use Illuminate\Support\Facades\File;
use App\Services\SizeService;


class FileController extends Controller
{

    public function viewFolderFiles($id, $folderName, SizeService $sizeService)
    {
        $deal = Deal::findOrFail($id);

        $folderPath = public_path("deals_main/{$deal->name}_{$deal->id}/{$folderName}");

        if (!File::exists($folderPath) || !File::isDirectory($folderPath)) {
            abort(404, "Folder not found.");
        }

        $files = File::files($folderPath);

        $filesData = [];
        foreach ($files as $file) {
            $filesData[] = [
                'name' => $file->getFilename(),
                'size' => $sizeService->formatSizeUnits($file->getSize()),
                'last_modified' => \Carbon\Carbon::createFromTimestamp($file->getMTime())->toDateTimeString(),
            ];
        }

        return view('backend.files.index', [
            'deal' => $deal,
            'folderName' => $folderName,
            'files' => $filesData,
        ]);
    }

}
