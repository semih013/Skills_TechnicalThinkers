<?php

namespace Database\Seeders;

use App\Models\Alert;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\DataSource;
use App\Models\Farmer;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Farmer::factory(50)->create();
        Alert::factory(10)->create();
        Message::factory(30)->create();

        DataSource::factory()->create(['name' => 'Weather Data', 'status' => 'Active', 'last_updated_at' => now()]);
        DataSource::factory()->create(['name' => 'Satellite Data', 'status' => 'Active', 'last_updated_at' => now()]);
        DataSource::factory()->create(['name' => 'Pest Detection', 'status' => 'Mocked', 'last_updated_at' => now()]);
        DataSource::factory()->create(['name' => 'Market Prices', 'status' => 'Mocked', 'last_updated_at' => now()]);
    }
}
