<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticatedCustom
{
    public function handle(Request $request, Closure $next)
    {
        // اگر کاربر لاگین است اجازه ورود به صفحه لاگین را نمی‌دهیم
        if (Auth::check() && Auth::user()->hasRole('student')) {
            return redirect()->route('client_dashboard'); // تغییر مسیر دلخواه
        } elseif (Auth::check() && Auth::user()->hasRole('teacher')) {
            return redirect()->route('teacher_index'); // تغییر مسیر دلخواه
        }elseif (Auth::check() && Auth::user()->hasRole('admin')) {
            return redirect()->route('admin_index'); // تغییر مسیر دلخواه
        }

        return $next($request);
    }
}
