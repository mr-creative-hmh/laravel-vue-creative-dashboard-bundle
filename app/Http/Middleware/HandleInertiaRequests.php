<?php

namespace App\Http\Middleware;

use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();

        // Check session first, then system settings, then config
        $locale = session('app_locale');
        if (! $locale) {
            $locale = SystemSetting::get('default_locale', config('app.locale', 'en'));
        }
        if (! $locale) {
            $locale = config('app.locale', 'en');
        }

        app()->setLocale($locale);

        $translations = [];
        $langFile = lang_path("{$locale}.json");
        if (file_exists($langFile)) {
            $translations = json_decode(file_get_contents($langFile), true) ?: [];
        }

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'auth' => [
                'user' => $user ? array_merge($user->toArray(), [
                    'avatar_url' => $user->avatar ? asset('storage/'.$user->avatar) : null,
                    'roles' => $user->getRoleNames()->toArray(),
                    'permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
                    'unread_notifications_count' => $user->unreadNotifications()->count(),
                    'recent_notifications' => $user->notifications()->take(5)->get()->map(function ($n) {
                        return [
                            'id' => $n->id,
                            'type' => class_basename($n->type),
                            'data' => $n->data,
                            'read_at' => $n->read_at ? $n->read_at->format('Y-m-d H:i:s') : null,
                            'created_at_human' => $n->created_at->diffForHumans(),
                        ];
                    })->toArray(),
                ]) : null,
                'is_impersonating' => $request->session()->has('impersonator_id'),
                'impersonator_name' => $request->session()->has('impersonator_id')
                    ? User::find($request->session()->get('impersonator_id'))?->name
                    : null,
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'warning' => fn () => $request->session()->get('warning'),
                'info' => fn () => $request->session()->get('info'),
                'status' => fn () => $request->session()->get('status'),
                'toast' => fn () => $request->session()->get('toast'),
            ],
            'locale' => $locale,
            'dir' => in_array($locale, ['ar', 'he', 'fa', 'ur']) ? 'rtl' : 'ltr',
            'translations' => $translations,
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        ];
    }
}
