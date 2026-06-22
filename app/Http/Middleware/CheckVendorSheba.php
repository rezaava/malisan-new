<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckVendorSheba
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {

        $user = auth()->user();

        if ($user && $user->hasRole('vendor') && $user->vendorProfile->status == 'wating') {
            return redirect()->route('vendor_singup');
        }
        if ($user && $user->hasRole('vendor') && $user->vendorProfile->status == 'request') {
            return redirect()->route('vendor_singup');
        }
        if ($user && $user->hasRole('vendor') && $user->vendorProfile->status == 'archived') {
            Auth::logout();

            return redirect('/login')
                ->with('error', 'حساب کاربری شما توسط مدیریت غیرفعال شده است');
        }


        return $next($request);
    }
}
