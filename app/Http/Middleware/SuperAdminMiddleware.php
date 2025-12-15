<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if (!$user) abort(403);

        $role = $user->role?->value ?? (string) $user->role;

        if ($role !== 'super_admin' && $role !== 'superAdmin') { // اختار واحدة بس بعد ما توحّد
            abort(403);
        }

        return $next($request);
    }
}
