<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Super Admin User
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->assignRole('Super Admin');

        // 2. Admin User
        $admin = User::firstOrCreate(
            ['email' => 'manager.admin@example.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('Admin');

        // 3. Manager User
        $manager = User::firstOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Operations Manager',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $manager->assignRole('Manager');

        // 4. Regular Demo User
        $regularUser = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $regularUser->assignRole('User');

        // 5. Generate additional dummy users using User factory if available
        if (class_exists(UserFactory::class)) {
            User::factory(15)->create()->each(function (User $u) {
                $u->assignRole('User');
            });
        }
    }
}
