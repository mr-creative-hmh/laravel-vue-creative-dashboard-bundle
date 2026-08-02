<?php

namespace Database\Seeders;

use App\Models\User;
use App\Notifications\SystemAlertNotification;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'hasan@mail.com')->first() ?? User::first();

        if ($admin) {
            // Unread notifications
            $admin->notify(new SystemAlertNotification(
                'System Update Completed',
                'Creative Starter Dashboard Kit v2.0 successfully deployed.',
                '/dashboard'
            ));

            $admin->notify(new SystemAlertNotification(
                'New User Registered',
                'User "Edyth Heidenreich" has joined the platform.',
                '/users'
            ));

            $admin->notify(new SystemAlertNotification(
                'Security Policy Updated',
                'Role "Super Admin" permissions matrix was modified.',
                '/roles'
            ));

            // Read notification
            $readNotif = $admin->notify(new SystemAlertNotification(
                'Excel Export Ready',
                'Your requested users export file is ready for download.',
                '/exports/users'
            ));
        }
    }
}
