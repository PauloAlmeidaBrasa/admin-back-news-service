<?php


namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// use database\seeders\serSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            \Database\Seeders\UserSeeder::class,
            
            // Add other seeders here as needed:
            // \Database\Seeders\PostSeeder::class,
            // \Database\Seeders\CommentSeeder::class,
        ]);

        // $this->call([
        //     UserSeeder::class,
        // ]);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
