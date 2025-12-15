<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user) abort(403);

        $role = $user->role?->value ?? $user->role;
        $role = strtolower(str_replace([' ', '-'], '_', (string) $role));

        // عدل القيم حسب Enum عندك: superAdmin أو super_admin
        if (!in_array($role, ['superadmin', 'super_admin'], true)) {
            abort(403);
        }

        return $next($request);
    }
}
