<?php

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckVendorActive
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->teacherProfile && $user->teacherProfile->status === 'archived') {

            Auth::logout();

            return redirect('/login')
                ->with('error', 'حساب کاربری شما توسط مدیریت غیرفعال شده است');
        }

        return $next($request);
    }
}
