<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();
        if (!$user) abort(403);

        // role ممكن Enum أو string
        $userRole = $user->role?->value ?? $user->role;

        // normalize
        $norm = fn($v) => strtolower(str_replace([' ', '-'], '_', (string) $v));

        $userRole = $norm($userRole);
        $roles = array_map($norm, $roles);

        if (!in_array($userRole, $roles, true)) {
            abort(403);
        }

        return $next($request);
    }
}
