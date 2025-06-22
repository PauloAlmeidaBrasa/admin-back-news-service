<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {

        $users = [
            [
                'name' => 'Paulo',
                'email' => 'paulo@eexample.com',
                'password' => Hash::make('123456'),
                'client_id' => 1,
                'access_level' => 4
            ],
            [
                'name' => 'user1',
                'email' => 'user1@example.com',
                'password' => Hash::make('123456'),
                'access_level' => 4
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
        
        User::factory()->count(5)->create();
    }
}