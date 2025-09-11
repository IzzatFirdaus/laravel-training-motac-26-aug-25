<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    /**
     * Switch the application locale and persist it to the session.
     */
    public function switch(Request $request, string $locale): RedirectResponse
    {
        $allowed = ['ms', 'en'];

        if (! in_array($locale, $allowed, true)) {
            $locale = config('app.locale');
        }

        $request->session()->put('locale', $locale);

        // Prefer redirect back, fall back to home
        return back()->withInput();
    }
}
