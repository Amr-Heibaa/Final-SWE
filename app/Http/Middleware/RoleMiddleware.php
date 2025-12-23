<?php

namespace App\Http\Middleware;

use App\Enums\RoleEnum;
use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();
        abort_unless($user, 403);

        $userRole = $user->role instanceof RoleEnum
            ? $user->role->value
            : (string) $user->role;

        $userRole = strtolower(trim($userRole));
        $roles = array_map(fn ($r) => strtolower(trim($r)), $roles);

        // optional alias support
        if ($userRole === 'superadmin') $userRole = 'super_admin';

        abort_unless(in_array($userRole, $roles, true), 403);

        return $next($request);
    }
}
