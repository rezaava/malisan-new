<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserHasPassword
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && empty($user->password)) {
            return redirect()->route('set_password_show');
        }

        if ($user->hasRole('vendor')) {
            return redirect()->route('vendor_index');
        }

        if ($user->hasRole('admin')) {
            return redirect()->route('admin_index');
        }

        return $next($request);
    }
}
