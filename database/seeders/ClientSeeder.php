<?php

namespace Database\Seeders;
use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB::table('clients')->truncate();

        $clients = [
            [
                'name' => 'Acme Corporation',
                'address' => '123 Business Rd, New York, NY 10001',
            ],
            [
                'name' => 'Globex Corporation',
                'address' => '456 Industry Ave, Chicago, IL 60601',
            ],
            [
                'name' => 'Initech',
                'address' => '789 Technology Blvd, San Francisco, CA 94103',
            ],
            [
                'name' => 'Umbrella Corporation',
                'address' => '100 Research Park, Raccoon City',
            ],
            [
                'name' => 'Wayne Enterprises',
            ]
        ];
        
        foreach ($clients as $client) {
            Client::create($client);
        }
        
        Client::factory()->count(5)->create();
    }
}
