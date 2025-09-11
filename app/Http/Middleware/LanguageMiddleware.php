<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageMiddleware
{
    /**
     * Handle an incoming request and set the application locale from query or session.
     */
    public function handle(Request $request, Closure $next)
    {
        $allowed = ['ms', 'en'];

        // Highest priority: explicit ?lang=xx on the URL
        $queryLocale = $request->query('lang');
        if (is_string($queryLocale) && in_array($queryLocale, $allowed, true)) {
            App::setLocale($queryLocale);
            $request->session()->put('locale', $queryLocale);

            return $next($request);
        }

        // Next: session locale (set by controller / previous requests)
        $sessionLocale = $request->session()->get('locale');
        if (is_string($sessionLocale) && in_array($sessionLocale, $allowed, true)) {
            App::setLocale($sessionLocale);

            return $next($request);
        }

        // Finally: use configured app locale
        App::setLocale(config('app.locale'));

        return $next($request);
    }
}
