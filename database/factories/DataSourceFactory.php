<?php

namespace Database\Factories;

use App\Models\DataSource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DataSource>
 */
class DataSourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Weather Data', 'Satellite Data', 'Pest Detection', 'Market Prices']),
            'status' => fake()->randomElement(['Active', 'Mocked']),
            'last_updated_at' => fake()->dateTimeBetween('-1 day', 'now'),
        ];
    }
}
