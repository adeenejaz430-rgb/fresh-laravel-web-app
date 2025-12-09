<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    // POST /api/upload   (expects form-data with "file")
    public function store(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|max:5120', // 5MB
            ]);

            $path = $request->file('file')->store('uploads', 'public');
            $url  = Storage::url($path);

            return response()->json([
                'success' => true,
                'url'     => $url,
                'path'    => $path,
            ], 200);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Server error during upload',
            ], 500);
        }
    }
}
