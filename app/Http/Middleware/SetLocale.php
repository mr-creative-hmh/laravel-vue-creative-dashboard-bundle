<?php

namespace App\Http\Middleware;

use App\Models\SystemSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('lang')) {
            $lang = strtolower($request->get('lang'));
            if (in_array($lang, ['en', 'ar'])) {
                session(['app_locale' => $lang]);
            }
        }

        // Check session first, then system settings, then config
        $locale = session('app_locale');

        if (! $locale) {
            $locale = SystemSetting::get('default_locale', config('app.locale', 'en'));
        }

        if (! $locale) {
            $locale = config('app.locale', 'en');
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
