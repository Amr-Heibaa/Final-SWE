<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();
        if (!$user) abort(403);

        // لو role Enum خد value، لو string استخدمه زي ما هو
        $userRole = is_object($user->role) && property_exists($user->role, 'value')
            ? $user->role->value
            : (string) $user->role;

        // normalize (case-insensitive)
        $userRole = strtolower($userRole);
        $roles = array_map(fn ($r) => strtolower(trim($r)), $roles);

        if (!in_array($userRole, $roles, true)) {
            abort(403);
        }

        return $next($request);
    }
}
