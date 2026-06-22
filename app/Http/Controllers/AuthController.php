<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login()
    {
        if (auth()->check()) {
            // اگر کاربر لاگین بود بر اساس نقش هدایتش کن
            $user = auth()->user();
            if ($user->hasRole('admin')) {
                return redirect()->route('index_admin');
            } elseif ($user->hasRole('teacher')) {
                return redirect()->route('index_teacher');
            } elseif ($user->hasRole('student')) {
                return redirect()->route('index_student');
            }
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    public function register()
    {
        if (auth()->check()) {
            // اگر کاربر لاگین بود بر اساس نقش هدایتش کن
            $user = auth()->user();
            if ($user->hasRole('admin')) {
                return redirect()->route('index_admin');
            } elseif ($user->hasRole('teacher')) {
                return redirect()->route('index_teacher');
            } elseif ($user->hasRole('student')) {
                return redirect()->route('index_student');
            }
            return redirect()->route('home');
        }
        return view('auth.register');
    }

    public function registerPost(Request $request)
    {
        // اعتبارسنجی
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'family' => 'required|string|max:191',
            'national' => 'required|string|size:10|unique:users,national',
            'mobile' => 'required|string|size:11|unique:users,mobile',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // ایجاد کاربر جدید
            $user = User::create([
                'name' => $request->name,
                'family' => $request->family,
                'national' => $request->national,
                'mobile' => $request->mobile,
                'password' => Hash::make($request->password),
                'role' => 3, // پیشفرض: دانشجو (role=2)
                'active' => 1,
                'email' => null, // میتونی از موبایل یا ایمیل استفاده کنی
            ]);

            $user->addRole('student');

            Auth::login($user);

            if ($user->hasRole('admin')) {
                return redirect()->route('admin.dashboard')->with('success', 'خوش آمدید مدیر گرامی');
            } elseif ($user->hasRole('teacher')) {
                return redirect()->route('index_teacher')->with('success', 'خوش آمدید استاد گرامی');
            } elseif ($user->hasRole('student')) {
                return redirect()->route('index_student')->with('success', 'خوش آمدید دانشجو گرامی');
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('danger', 'خطا در ثبت نام: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function loginPost(Request $request)
    {
        // اعتبارسنجی
        $validator = Validator::make($request->all(), [
            'national' => 'required|string|size:10',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // جستجوی کاربر با کد ملی
        $user = User::where('national', $request->national)->first();

        if (!$user) {
            return redirect()->back()
                ->with('error', 'کد ملی یا رمز عبور اشتباه است')
                ->withInput();
        }

        // بررسی پسورد
        if (!Hash::check($request->password, $user->password)) {
            return redirect()->back()
                ->with('error', 'کد ملی یا رمز عبور اشتباه است')
                ->withInput();
        }

        // بررسی فعال بودن کاربر
        // if ($user->active != 1) {
        //     return redirect()->back()
        //         ->with('error', 'حساب کاربری شما غیرفعال است')
        //         ->withInput();
        // }

        // ورود کاربر
        Auth::login($user, $request->has('remember'));

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard')->with('success', 'خوش آمدید مدیر گرامی');
        } elseif ($user->hasRole('teacher')) {
            return redirect()->route('index_teacher')->with('success', 'خوش آمدید استاد گرامی');
        } elseif ($user->hasRole('student')) {
            return redirect()->route('index_student')->with('success', 'خوش آمدید دانشجو گرامی');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'با موفقیت خارج شدید');
    }

    function roleFun() {
        $admin = new Role();
        $admin->name = 'admin';
        $admin->display_name = 'مدیر سیستم';
        $admin->description = 'مدیریت کامل سیستم';
        $admin->save();

        $teacher = new Role();
        $teacher->name = 'teacher';
        $teacher->display_name = 'استاد';
        $teacher->description = 'مدیریت دوره‌ها';
        $teacher->save();

        $student = new Role();
        $student->name = 'student';
        $student->display_name = 'دانشجو';
        $student->description = 'دسترسی به دوره‌ها';
        $student->save();
    }
}