<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Prevent duplicate seed if running multiple times
        User::firstOrCreate(
            ['email' => 'admin@toko.com'],
            [
                'name' => 'Tante Admin',
                'role' => 'admin',
                'password' => Hash::make('password123'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'kasir@toko.com'],
            [
                'name' => 'Kasir Toko',
                'role' => 'staff',
                'password' => Hash::make('password123'),
            ]
        );
    }
}
