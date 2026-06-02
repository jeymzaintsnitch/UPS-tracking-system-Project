<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Seeds the initial roles (Admin, Staff) and default user accounts
 * for the UPS Tracking System.
 */
class RoleAndUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Roles via Spatie
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $staffRole = Role::firstOrCreate(['name' => 'Staff', 'guard_name' => 'web']);

        // 2. Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@ups-tracker.test'],
            [
                'name'              => 'System Admin',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole($adminRole);

        // 3. Create Staff User
        $staff = User::firstOrCreate(
            ['email' => 'staff@ups-tracker.test'],
            [
                'name'              => 'Staff Member',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $staff->assignRole($staffRole);
    }
}