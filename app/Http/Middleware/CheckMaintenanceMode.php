<?php

namespace App\Http\Middleware;

use App\Models\SystemSetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->runningUnitTests()) {
            return $next($request);
        }

        // Check if maintenance mode is enabled in system settings
        $maintenanceMode = SystemSetting::get('maintenance_mode', '0') === '1';

        if ($maintenanceMode) {
            // Always allow logout and locale updates
            if ($request->is('logout') || $request->is('locale')) {
                return $next($request);
            }

            // Allow access to super admins or users with settings.edit permission
            if ($request->user() && ($request->user()->hasRole('Super Admin') || $request->user()->can('settings.edit'))) {
                return $next($request);
            }

            // If user attempts POST /login while maintenance mode is active, reject unauthorized users
            if ($request->is('login') && $request->isMethod('post')) {
                $response = $next($request);

                // Check if user was authenticated during this request
                if ($request->user()) {
                    $user = $request->user();
                    if (! $user->hasRole('Super Admin') && ! $user->can('settings.edit')) {
                        Auth::guard('web')->logout();
                        $request->session()->invalidate();
                        $request->session()->regenerateToken();

                        $errorMessage = app()->getLocale() === 'ar'
                            ? 'النظام حالياً تحت الصيانة. يمكن للمسؤولين المصرّح لهم فقط تسجيل الدخول.'
                            : 'System is currently under maintenance. Only authorized administrators can log in.';

                        return redirect()->route('login')->withErrors([
                            'email' => $errorMessage,
                        ]);
                    }
                }

                return $response;
            }

            // Allow access to authentication GET routes
            if ($request->is('login') || $request->is('register') || $request->is('password/*') || $request->is('forgot-password')) {
                return $next($request);
            }

            // Allow access to error pages
            if ($request->is('maintenance') || $request->is('404') || $request->is('403') || $request->is('500')) {
                return $next($request);
            }

            // For Inertia requests, redirect to maintenance page
            if ($request->header('X-Inertia')) {
                return redirect()->route('maintenance');
            }

            // For regular requests, show maintenance view
            return response()->view('errors.503', [], 503);
        }

        return $next($request);
    }
}
