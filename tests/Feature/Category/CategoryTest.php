<?php

namespace Tests\Feature;

use Tests\TestCase;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Client;
use App\Models\Category;
use App\Models\User;
// use Illuminate\Foundation\Testing\DatabaseMigrations;



class CategoryTest extends TestCase
{
    use RefreshDatabase;


    protected $client;
    protected $category;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = Client::factory()->create();

          $this->user = User::factory()->create([
            'name' => 'userTests',
            'email' => 'test@example.com',
            'password' => bcrypt('123456'),
            'client_id' => $this->client->id,
            'access_level' => 3
        ]);

        $this->category = Category::factory()->create([
            'name' => 'category 1',
            'description' => 'description 1',
            'client_id' => $this->client->id
        ]);
        
        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => '123456'
        ]);

        $this->token = $response->json('access_token');
    }
    public function test_returns_json_object_with_category_data(): void
    {
        // dd(env('APP_ENV'), config('database.default'));


        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/v1/category/get-category");


        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'category' => [
                        '*' => [
                            'name',
                            'description'
                        ]
                    ]
                ],
            ]);

    }
    public function test_returns_unauthorized_for_invalid_token(): void
    {
        $invalidToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMC4wLjAuMDo4MDAwL2FwaS9sb2dpbiIsImlhdCI6MTc1MjQ5Mjc2NywiZXhwIjoxNzUyNDk2MzY3LCJuYmYiOjE3NTI0OTI3NjcsImp0aSI6IlhiYldFdzRoQll4aHB3dmciLCJzdWIiOiIxIiwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyIsImNsaWVudF9pZCI6MSwiYWNjZXNzX2xldmVsIjozLCJuYW1lIjoiUGF1bG8ifQ.YWdwXcBFsnQyR3qwJM2JoUb2qsX686x8rmcNhrSA4-M";  
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $invalidToken,
        ])->getJson("/api/v1/category/get-category");
            

        $response->assertStatus(401)
            ->assertJson([
                'error'=> 'Token is Invalid'
            ]);

    }
    public function test_returns_json_on_deleting_category(): void
    {
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson("/api/v1/category/delete",[ "category_ID" => $this->category->id ]);
           
        $response->assertStatus(200)
            ->assertJsonPath('code', 'SUCCESS');

        $this->assertStringContainsString('deleted', $response->json('message'));

    }

    /** @test */
    public function test_updates_category_data_successfully()
    {
        $payload = [
            'category_ID' => $this->category->id,
            'name' => 'New category name',
            'description' => 'new category description',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->patchJson('/api/v1/category/update/'.$this->category->id.'', $payload);


        $response->assertStatus(200)
            ->assertJsonPath('code', 'SUCCESS')
            ->assertJson([
                'message' => 'category updated successfully',
            ]);

        // Ensure DB reflects the change
        $this->assertDatabaseHas('category', [
            'id' => $this->category->id,
            'name' => 'New category name',
            'description' => 'new category description',
        ]);

        
    }

        /** @test */
    public function test_returns_error_if_user_not_found()
    {
        $payload = [
            'user_ID' => 999999,
            'name' => 'Fake User',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->patchJson('/api/v1/user/update/', $payload);

        $response->assertStatus(404)
            ->assertJsonPath('code', 'NOTFOUND')
            ->assertJson([
                'message' => 'User not found',
            ]);
    }
    public function test_returns_generic_error_on_internal_server_errors() {

        $this->mock(\App\Services\category\CategoryService::class, function ($mock) {
        $mock->shouldReceive('update')
             ->andThrow(new \Exception('Internal Server Error'));
        });

        $payload = [
            'category_ID' => 999,
            'name' => 'Test Name'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->patchJson('/api/v1/category/update/'.$this->category->id.'', $payload);

        $response->assertStatus(500)
            ->assertJson([
                'message' => 'Internal Server Error'
            ]);

    }

 
}
