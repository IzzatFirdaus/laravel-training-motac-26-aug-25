<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if ($user === null) {
            return redirect()->guest(route('login'));
        }

        // If no roles specified, allow
        if (empty($roles)) {
            return $next($request);
        }

        $userRole = strtolower((string) ($user->role ?? ''));
        $allowed = array_map('strtolower', $roles);

        if (! in_array($userRole, $allowed, true)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
