<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|max:5120' // 5MB
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $destinationPath = public_path('uploads/images');
        
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        
        $file->move($destinationPath, $fileName);
        
        return response()->json([
            'files' => [
                [
                    'url' => asset('uploads/images/' . $fileName),
                    'name' => $fileName,
                    'size' => $file->getSize()
                ]
            ]
        ]);
    }

    public function uploadVideo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:mp4,avi,mov,wmv,flv|max:20480' // 20MB
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $destinationPath = public_path('uploads/videos');
        
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        
        $file->move($destinationPath, $fileName);
        
        return response()->json([
            'files' => [
                [
                    'url' => asset('uploads/videos/' . $fileName),
                    'name' => $fileName,
                    'size' => $file->getSize()
                ]
            ]
        ]);
    }
}