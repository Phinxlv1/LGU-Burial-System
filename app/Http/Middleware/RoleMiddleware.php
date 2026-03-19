<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $allowedRoles = explode('|', $roles);

        foreach ($allowedRoles as $role) {
            if (auth()->user()->hasRole(trim($role))) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized.');
    }
}