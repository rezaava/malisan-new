<?php

namespace App\Http\Controllers;

use App\Models\Angizesh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    /**
     * نمایش صفحه مدیریت پیام‌های انگیزشی
     */
    public function angizesh_index()
    {
        $angizeshes = Angizesh::orderBy('level', 'asc')->get();
        
        // سطح‌بندی برای نمایش
        $levelLabels = [
            1 => 'نمره 20',
            2 => 'نمره 18 تا کمتر از 20',
            3 => 'نمره 15 تا کمتر از 18',
            4 => 'نمره 12 تا کمتر از 15',
            5 => 'نمره 10 تا کمتر از 12',
            6 => 'نمره زیر 10',
            7 => 'پیام ورود',
        ];
        
        return view('admin.angizesh', compact('angizeshes', 'levelLabels'));
    }

    /**
     * ذخیره پیام انگیزشی جدید
     */
    public function angizesh_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|string|max:5000',
            'level' => 'required|in:1,2,3,4,5,6,7',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $angizesh = Angizesh::create([
                'text' => $request->text,
                'level' => $request->level,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'پیام با موفقیت اضافه شد',
                'data' => $angizesh
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در ذخیره پیام: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ویرایش پیام انگیزشی
     */
    public function angizesh_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|string|max:5000',
            'level' => 'required|in:1,2,3,4,5,6,7',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $angizesh = Angizesh::findOrFail($id);
            $angizesh->update([
                'text' => $request->text,
                'level' => $request->level,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'پیام با موفقیت ویرایش شد',
                'data' => $angizesh
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در ویرایش پیام: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف پیام انگیزشی
     */
    public function angizesh_destroy($id)
    {
        try {
            $angizesh = Angizesh::findOrFail($id);
            $angizesh->delete();

            return response()->json([
                'success' => true,
                'message' => 'پیام با موفقیت حذف شد'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در حذف پیام: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * دریافت اطلاعات یک پیام برای ویرایش (AJAX)
     */
    public function angizesh_edit($id)
    {
        try {
            $angizesh = Angizesh::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $angizesh
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در دریافت اطلاعات: ' . $e->getMessage()
            ], 500);
        }
    }
}