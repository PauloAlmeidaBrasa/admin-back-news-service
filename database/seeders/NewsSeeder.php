<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;
// use Illuminate\Support\Facades\Hash;

class NewsSeeder extends Seeder
{
    public function run()
    {

        $news = [
            [
                'title' => 'new 1',
                'subtitle' => 'subtitle 1',
                'category' => 1,
                'text' => 'text 1',
                'client_id' => 1
            ],
            [
                'title' => 'new 2',
                'subtitle' => 'subtitle 2',
                'category' => 2,
                'text' => 'text 2',
                'client_id' => 1
            ],
             [
                'title' => 'new 3',
                'subtitle' => 'subtitle 3',
                'category' => 1,
                'text' => 'text 3',
                'client_id' => 1
            ]
        ];

        foreach ($news as $new) {
            News::create($new);
        }
        
        // News::factory()->count(10)->create();
    }
}