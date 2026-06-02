<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Main database seeder for the UPS Tracking System.
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleAndUserSeeder::class,
        ]);
    }
}
