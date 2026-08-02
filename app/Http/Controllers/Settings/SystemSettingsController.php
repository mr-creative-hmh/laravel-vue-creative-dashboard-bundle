<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class SystemSettingsController extends Controller
{
    /**
     * Display general system settings page.
     */
    public function edit(): Response
    {
        Gate::authorize('settings.view');

        $settings = [
            'app_name' => SystemSetting::get('app_name', 'Creative Starter Dashboard'),
            'support_email' => SystemSetting::get('support_email', 'support@example.com'),
            'default_locale' => SystemSetting::get('default_locale', 'en'),
            'maintenance_mode' => SystemSetting::get('maintenance_mode', '0') === '1',
        ];

        return Inertia::render('settings/System', [
            'settings' => $settings,
        ]);
    }

    /**
     * Update system settings.
     */
    public function update(Request $request): RedirectResponse
    {
        Gate::authorize('settings.edit');

        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'support_email' => 'required|email|max:255',
            'default_locale' => 'required|string|in:en,ar',
            'maintenance_mode' => 'required|boolean',
        ]);

        SystemSetting::set('app_name', $validated['app_name']);
        SystemSetting::set('support_email', $validated['support_email']);
        SystemSetting::set('default_locale', $validated['default_locale']);
        SystemSetting::set('maintenance_mode', $validated['maintenance_mode'] ? '1' : '0');

        activity()
            ->causedBy($request->user())
            ->log('Updated system configuration settings');

        return back()->with('success', 'System settings updated successfully.');
    }
}
