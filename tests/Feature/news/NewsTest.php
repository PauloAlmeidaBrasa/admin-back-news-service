<?php

namespace Tests\Feature;

use Tests\TestCase;

use App\Models\Client;
use App\Models\News;
use App\Models\User;

class NewsTest extends TestCase
{

    protected $client;
    protected $news;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->artisan('db:seed');


        $this->client = Client::factory()->create();

          $this->user = User::factory()->create([
            'name' => 'userTests',
            'email' => 'test@example.com',
            'password' => bcrypt('123456'),
            'client_id' => $this->client->id,
            'access_level' => 3
        ]);

        $this->news = News::factory()->create([
            'title' => 'title 1',
            'subtitle' => 'subtitle 1',
            'text' => 'text 1',
            'client_id' => $this->client->id,
            'category' => 1
        ]);
        
        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => '123456'
        ]);

        $this->token = $response->json('access_token');
    }
    public function test_returns_json_object_with_news_data(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/v1/news/get-news");


        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'news' => [
                        '*' => [
                            'id',
                            'title'
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
        ])->getJson("/api/v1/news/get-news");
            

        $response->assertStatus(401)
            ->assertJson([
                'error'=> 'Token is Invalid'
            ]);

    }
    public function test_returns_json_on_deleting_news(): void
    {
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson("/api/v1/news/delete",[ "news_ID" => $this->news->id ]);
           
        $response->assertStatus(200)
            ->assertJsonPath('code', 'SUCCESS');

        $this->assertStringContainsString('deleted', $response->json('message'));

    }

    /** @test */
    public function test_updates_news_data_successfully()
    {
        $payload = [
            'news_ID' => $this->news->id,
            'title' => 'New title',
            'subtitle' => 'new subtitle',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->patchJson('/api/v1/news/update/'.$this->news->id.'', $payload);


        $response->assertStatus(200)
            ->assertJsonPath('code', 'SUCCESS')
            ->assertJson([
                'message' => 'news updated successfully',
            ]);

        // Ensure DB reflects the change
        $this->assertDatabaseHas('news', [
            'id' => $this->news->id,
            'title' => 'New title',
            'subtitle' => 'new subtitle',
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
        ])->patchJson('/api/v1/user/update/'.$this->news->id.'', $payload);

        $response->assertStatus(404)
            ->assertJsonPath('code', 'NOTFOUND')
            ->assertJson([
                'message' => 'User not found',
            ]);
    }
    public function test_returns_generic_error_on_internal_server_errors() {

        $this->mock(\App\Services\news\NewsService::class, function ($mock) {
        $mock->shouldReceive('update')
             ->andThrow(new \Exception('Internal Server Error'));
        });

        $payload = [
            'news_ID' => 999,
            'name' => 'Test Name'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->patchJson('/api/v1/news/update/'.$this->news->id.'', $payload);

        $response->assertStatus(500)
            ->assertJson([
                'message' => 'Internal Server Error'
            ]);

    }

 
}
