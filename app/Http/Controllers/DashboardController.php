<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    /**
     * Display the main analytics dashboard overview.
     */
    public function index(Request $request): Response
    {
        // KPI Summary Stats
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $totalRoles = Role::count();
        $totalLogs = Activity::count();

        // User Registrations over the past 6 months (Monthly trend)
        $months = collect(range(5, 0))->map(function ($i) {
            return now()->subMonths($i);
        });

        $userGrowthLabels = $months->map(fn ($m) => $m->format('M Y'))->toArray();
        $userGrowthData = $months->map(function ($m) {
            return User::whereYear('created_at', $m->year)
                ->whereMonth('created_at', $m->month)
                ->count();
        })->toArray();

        // Role Distribution (Users per role)
        $rolesWithCount = Role::withCount('users')->get();
        $roleDistributionLabels = $rolesWithCount->pluck('name')->toArray();
        $roleDistributionData = $rolesWithCount->pluck('users_count')->toArray();

        // Recent Activity Logs (Latest 6)
        $recentLogs = Activity::with('causer')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'description' => $log->description,
                    'event' => $log->event,
                    'causer' => $log->causer ? $log->causer->name : 'System',
                    'created_at_human' => $log->created_at->diffForHumans(),
                ];
            });

        // Recent Registered Users (Latest 5)
        $recentUsers = User::with('roles')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar_url' => $user->avatar ? asset('storage/'.$user->avatar) : null,
                    'roles' => $user->getRoleNames()->toArray(),
                    'created_at_human' => $user->created_at->diffForHumans(),
                ];
            });

        return Inertia::render('Dashboard', [
            'stats' => [
                'total_users' => $totalUsers,
                'active_users' => $activeUsers,
                'total_roles' => $totalRoles,
                'total_logs' => $totalLogs,
            ],
            'charts' => [
                'user_growth' => [
                    'labels' => $userGrowthLabels,
                    'series' => $userGrowthData,
                ],
                'role_distribution' => [
                    'labels' => $roleDistributionLabels,
                    'series' => $roleDistributionData,
                ],
            ],
            'recentLogs' => $recentLogs,
            'recentUsers' => $recentUsers,
        ]);
    }
}
