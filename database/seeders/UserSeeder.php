<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@skportal.com',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
        ]);

        // Create additional test users
        User::create([
            'name' => 'SK Chairperson',
            'email' => 'chair@skportal.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'SK Secretary',
            'email' => 'secretary@skportal.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
    }
}
