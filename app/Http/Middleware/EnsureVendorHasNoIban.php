<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureVendorHasNoIban
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // اگر لاگین نیست، بفرست به لاگین
        if (!$user) {
            return redirect()->route('login');
        }

        // اگر نقش vendor ندارد، می‌توانی 403 بدهی یا ریدایرکتش کنی
        // اگر از spatie/laravel-permission استفاده می‌کنی:
        if (!$user->hasRole('vendor')) {
            abort(403, 'دسترسی غیرمجاز');
        }

        $profile = $user->vendorProfile;

        // اگر هنوز پروفایل ندارد، اجازه بده فرم را پر کند
        if ($profile->status == 'wating') {
            return $next($request);
        }
        if ($profile->status == 'request' || $profile->status == 'archived') {
            return redirect()
                ->route('client_index');
        }

        return redirect()
            ->route('vendor_index');
    }
}
