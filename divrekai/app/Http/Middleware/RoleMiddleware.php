<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$roles)
{
    // belum login
    if (!auth()->check()) {
        abort(403);
    }

    // user tidak punya role
    if (!auth()->user()->role) {
        abort(403, 'Role belum diatur');
    }

    // role tidak sesuai
    if (!in_array(auth()->user()->role->username, $roles)) {
        abort(403);
    }

    return $next($request);
    }
}
