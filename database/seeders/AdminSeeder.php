<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Nebula',
            'email' => 'admin@nebulastore.test',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_verified' => true,
            'wallet_balance' => 0,
        ]);
    }
}
