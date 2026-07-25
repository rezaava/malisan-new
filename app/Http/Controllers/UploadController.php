<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function uploadImage(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time().'_'.$file->getClientOriginalName();

            $file->move('uploads/images', $filename);

            return response()->json([
                'files' => [
                    ['url' => asset('uploads/images/' . $filename)]
                ]
            ]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }

    public function uploadVideo(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time().'_'.$file->getClientOriginalName();

            $file->move('uploads/videos', $filename);


            return response()->json([
                'files' => [
                    ['url' => asset('uploads/videos/' . $filename)]
                ]
            ]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }
}
