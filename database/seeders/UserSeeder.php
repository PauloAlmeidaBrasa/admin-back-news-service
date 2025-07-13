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
                'access_level' => 3
            ],
            [
                'name' => 'userEditor',
                'email' => 'user1@example.com',
                'password' => Hash::make('123456'),
                'client_id' => 1,
                'access_level' => 2
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
        
        User::factory()->count(10)->create();
    }
}