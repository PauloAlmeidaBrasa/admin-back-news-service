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
    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
 
        $this->client = Client::factory()->create();

        // Create a test user and get JWT token
        $this->user = User::factory()->create([
            'name' => 'userTests',
            'email' => 'test@example.com',
            'password' => bcrypt('123456'),
            'client_id' => $this->client->id,
            'access_level' => 3
        ]);
        
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => '123456'
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
        ])->getJson("/api/user/get-users");

        // $response->dump();

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'users' => [
                        '*' => [
                            'name',
                            'email'
                        ]
                    ]
                ],
            ]);
            // ->assertJsonCount(3, 'data.users');

    }

 
}
