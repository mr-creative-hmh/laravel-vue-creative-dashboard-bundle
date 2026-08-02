<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    /**
     * Switch application locale.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $request->validate([
            'locale' => ['required', 'string', 'in:en,ar'],
        ]);

        $locale = strtolower($request->input('locale'));
        session(['app_locale' => $locale]);
        app()->setLocale($locale);

        return back()->with('success', 'Language updated successfully.');
    }
}
