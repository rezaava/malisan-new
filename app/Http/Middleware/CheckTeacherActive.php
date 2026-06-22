<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckTeacherActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->teacherProfile) {

            if ($user->teacherProfile->status === 'archived') {

                Auth::logout();

                return redirect()->route('login')
                    ->with('error', 'حساب معلمی شما توسط مدیریت مسدود شده است');
            }
        }

        return $next($request);
    }
}
