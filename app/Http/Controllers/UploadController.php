<?php
// app/Http/Controllers/UploadController.php

namespace App\Http\Controllers;

use App\Services\CloudinaryService;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function store(Request $request, CloudinaryService $cloudinary)
    {
        $request->validate([
            'file' => ['required', 'file', 'image', 'max:5120'], // 5MB example
        ]);

        try {
            $file     = $request->file('file');
            $fileUrl  = $cloudinary->uploadFile($file, 'products');

            if (!$fileUrl) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to upload image',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'url'     => $fileUrl,
            ]);
        } catch (\Throwable $e) {
            \Log::error('Cloudinary upload error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Upload failed',
            ], 500);
        }
    }
}
