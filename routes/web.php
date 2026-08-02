<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ImpersonateController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');
Route::inertia('/maintenance', 'errors/Maintenance')->name('maintenance');
Route::inertia('/404', 'errors/404')->name('404');
Route::inertia('/403', 'errors/403')->name('403');
Route::inertia('/500', 'errors/500')->name('500');

Route::post('locale', LocaleController::class)->name('locale.update');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Data Exports
    Route::get('exports/users', [ExportController::class, 'exportUsers'])->name('exports.users');
    Route::get('exports/logs', [ExportController::class, 'exportLogs'])->name('exports.logs');

    // Notifications
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::post('notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::delete('notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    // User Impersonation
    Route::post('impersonate/leave', [ImpersonateController::class, 'leave'])->name('impersonate.leave');
    Route::post('impersonate/{user}', [ImpersonateController::class, 'take'])->name('impersonate.take');

    // Core Dashboard Modules
    Route::get('logs', [ActivityLogController::class, 'index'])->name('logs.index');
    Route::post('logs/{activity}/undo', [ActivityLogController::class, 'undo'])->name('logs.undo');
    Route::post('users/bulk-action', [UserController::class, 'bulkAction'])->name('users.bulk-action');
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class)->only(['index', 'store', 'destroy']);
    Route::post('permissions/generate-module', [PermissionController::class, 'generateModule'])->name('permissions.generate-module');
});

require __DIR__.'/settings.php';
