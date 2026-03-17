<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $allowedRoles = explode('|', $roles);

        if (!in_array(auth()->user()->role, $allowedRoles)) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
