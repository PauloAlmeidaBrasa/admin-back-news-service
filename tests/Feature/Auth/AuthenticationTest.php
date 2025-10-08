<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use Mockery;
use Tymon\JWTAuth\Facades\JWTAuth;



class AuthenticationTest extends TestCase
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
        // $this->user->dump();

        
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => '123456'
        ]);
        
        $this->token = $response->json('access_token');
    }
    /** @test */
    public function it_returns_token_with_valid_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => '123456'
        ]);


        $response->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_in'
            ]);
    }

    /** @test */
    public function it_returns_error_with_invalid_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(401)
            ->assertJson([
                // 'success' => false,
                'error' => 'invalid_credentials',
                'message' => 'Email or password is incorrect'
            ]);
    }



    /** @test */
    public function it_returns_generic_error_on_internal_server_errors()
    {


        // Force a server error by mocking JWTAuth to throw an exception
        JWTAuth::shouldReceive('attempt')
            ->once()
            ->andThrow(new \RuntimeException('Internal server error'));

        $response = $this->postJson('/api/login', [
            'email' => 'test@1example.com',
            'password' => 'validpassword'
        ]);

        $response->assertStatus(500)
            ->assertJson([
                // 'success' => false,
                'message' => 'Authentication service unavailable'
            ])
            ->assertJsonMissing([
                'error' => 'Internal server error' // Ensure detailed error isn't exposed
            ]);
    }
    public function test_returns_unauthorized_for_invalid_token(): void
    {
        $invalidToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMC4wLjAuMDo4MDAwL2FwaS9sb2dpbiIsImlhdCI6MTc1MjQ5Mjc2NywiZXhwIjoxNzUyNDk2MzY3LCJuYmYiOjE3NTI0OTI3NjcsImp0aSI6IlhiYldFdzRoQll4aHB3dmciLCJzdWIiOiIxIiwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyIsImNsaWVudF9pZCI6MSwiYWNjZXNzX2xldmVsIjozLCJuYW1lIjoiUGF1bG8ifQ.YWdwXcBFsnQyR3qwJM2JoUb2qsX686x8rmcNhrSA4-M";  
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $invalidToken,
        ])->getJson("/api/user/get-users");
            
        $response->dump();

        $response->assertStatus(401)
            ->assertJson([
                'message'=> 'Token has expired'

            ]);

    }
}
