<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Paulo',
            'email' => 'paulo@example.com',
            'password' => Hash::make('123456'),
            // Add any other required fields for your User model
        ]);
        
        // Optional: Create additional test users
        //User::factory()->count(5)->create(); // If using factories
    }
}