<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Mockery;
use Tymon\JWTAuth\Facades\JWTAuth;



class AuthenticationTest extends TestCase
{
    use RefreshDatabase;


    // protected function setUp(): void
    // {
    //     parent::setUp();
        
    //     // This creates a user IN THE TEST DATABASE only
    //     $this->user = User::factory()->create([
    //         'email' => 'test@example.com',
    //         'password' => bcrypt('validpassword')
    //     ]);
    // }
    /** @test */
    public function it_returns_token_with_valid_credentials()
    {
        $user = User::factory()->create([
            'name' => 'userTeste',
            'email' => 'test1@example.com',
            'password' => bcrypt('validpassword')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test1@example.com',
            'password' => 'validpassword'
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
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('validpassword')
        ]);

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
        // Create a test user
        $user = User::factory()->create([
            'email' => 'test1@example.com',
            'password' => bcrypt('validpassword')
        ]);

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
}
