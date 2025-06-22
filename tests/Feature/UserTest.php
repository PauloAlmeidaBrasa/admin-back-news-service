<?php

namespace Tests\Feature;

use Tests\TestCase;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Client;
use App\Models\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected $client;
    protected $users;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = Client::factory()->create();

        $this->users = User::factory()
            ->count(3)
            ->create(['client_id' => $this->client->id]);

        User::factory()->create(['client_id' => Client::factory()->create()->id]);

        // Create a test user and get JWT token
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('123')
        ]);
        
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => '123'
        ]);
        // dd($response->json());
        // $response->dump();
        // logger($response->json());
        
        $this->token = $response->json('access_token');
    }
    public function test_returns_json_object_with_data(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/users-by-client?client_id=".$this->client->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'users' => [
                        '*' => [
                            'id',
                            'name',
                            'email',
                            'client_id'
                        ]
                    ]
                ],
            ])
            ->assertJsonCount(3, 'data.users');
    }

 
}
