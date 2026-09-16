<?php

namespace App\Http\Controllers;

use App\Models\Angizesh;
use App\Models\Survey;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // دریافت پیام انگیزشی
        $message = Angizesh::whereIn('level', [7, 8])
            ->inRandomOrder()
            ->first();

        $massage = Angizesh::whereNotIn('level', [8, 7])->count();

        // دریافت پیام انگیزشی
        $survey = Survey::count();
        
        $users = User::count();
        $limitedUser = User::where('limited',1)->count();

        return view('admin.index', compact('users','limitedUser','user', 'message', 'massage', 'survey'));
    }

    public function adminShowUsers(Request $request)
    {
        $users = User::orderBy('id','desc')->get();

        return view('admin.show_users', compact('users'));
    }
    public function adminShowLimitedUsers(Request $request)
    {
        $users = User::orderBy('id','desc')->where('limited',1)->get();

        return view('admin.show_limited_users', compact('users'));
    }

    //when admin clicks on reset password for user
    public function resetPasswordRequest(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $status = Password::broker()->sendResetLink(['email' => $user->email]);

        return $status == Password::RESET_LINK_SENT
            ? back()->with(['status' => $status])
            : back()->withErrors(['status' => $status]);

    }

    //this fucntion performs checks on password and token and complete the reset password proccess
    public function resetPasswordComplete(Request $request)
    {
        Validator::make($request->all(), [
            'token' => 'required',
            'password' => 'required|string|confirmed',
            'email' => 'required|email'
        ]);


        $resetData = DB::table('password_reset_tokens')->where('email', $request->email)
            ->first();

        //check if token is invalid
        if (!$resetData || Hash::check($resetData->token, $request->token)) {
            return back()->withErrors(['error' => "token is invalid", 'email' => $request->email]);
        }

        $user = User::where('email', $request->email);

        //making new password and storing it
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        $user->save();

        //deleting reset token for this user
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with(['status' => 'password changed successfully']);
    }
    public function toggleLimitUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->limited = !$user->limited;
        $user->save();

        return response()->json([
            'success' => true,
            'limited' => (bool) $user->limited,
            'message' => $user->limited
                ? 'کاربر با موفقیت محدود شد.'
                : 'محدودیت کاربر با موفقیت برداشته شد.'
        ]);
    }
}