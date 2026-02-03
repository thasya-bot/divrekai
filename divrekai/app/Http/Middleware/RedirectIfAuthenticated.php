<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {

                $role = Auth::user()->role->username ?? null;

                return match ($role) {
                    'admin_unit' => redirect()->route('pendapatan.index'),
                    'admin_pic'  => redirect()->route('admin.dashboard'),
                    'pimpinan'   => redirect()->route('pimpinan.dashboard'),
                    default      => redirect()->route('beranda'),
                };
            }
        }

        return $next($request);
    }
}
