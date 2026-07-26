<?php

namespace App\Http\Controllers;

use App\Models\Angizesh;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // دریافت پیام انگیزشی
        $message = Angizesh::whereIn('level', [7, 8])
            ->inRandomOrder()
            ->first();
        
            $massage = Angizesh::whereNotIn('level', [8,7])->count();

        return view('admin.index', compact('user','message','massage'));
    }
}