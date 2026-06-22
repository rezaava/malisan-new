<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // ثبت نام
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'family' => 'required|string|max:255',
            'national' => 'required|string|size:10|unique:users',
            'mobile' => 'required|string|size:11|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'type' => 'nullable|in:1,2',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'خطا در اعتبارسنجی',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'family' => $request->family,
            'national' => $request->national,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            'type' => $request->type ?? 2,
            'active' => 1,
        ]);

        return response()->json([
            'message' => 'ثبت نام موفقیت‌آمیز بود',
            'user' => $user
        ], 201);
    }

    // ورود
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|string|size:11',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'خطا در اعتبارسنجی',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('mobile', $request->mobile)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'اطلاعات وارد شده صحیح نیست'
            ], 401);
        }

        $token = $user->createToken('auth_token')->accessToken;

        return response()->json([
            'message' => 'ورود موفقیت‌آمیز',
            'token' => $token,
            'user' => $user
        ]);
    }

    // خروج
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'خروج موفقیت‌آمیز'
        ]);
    }

    // اطلاعات کاربر
    public function user(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ]);
    }
}