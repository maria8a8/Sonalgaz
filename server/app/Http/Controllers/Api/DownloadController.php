<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadController extends Controller
{
    public function download(Request $request)
    {
        $filePath = $request->query('path');
        
        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        $filename = basename($filePath);
        $mimeType = Storage::disk('public')->mimeType($filePath);

        return response()->streamDownload(function () use ($filePath) {
            echo Storage::disk('public')->get($filePath);
        }, $filename, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
