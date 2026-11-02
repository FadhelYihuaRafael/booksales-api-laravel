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
        // Akun Admin
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('password'),
                'role'     => 'admin',
            ]
        );

        // Akun Customer
        User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name'     => 'Customer Satu',
                'password' => Hash::make('password'),
                'role'     => 'customer',
            ]
        );

        // Akun User Biasa
        User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name'     => 'Regular User',
                'password' => Hash::make('password'),
                'role'     => 'user',
            ]
        );
    }
}
