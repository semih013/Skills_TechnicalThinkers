<?php

namespace Database\Factories;

use App\Models\Alert;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Alert>
 */
class AlertFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'message' => fake()->sentence(10),
            'region' => fake()->randomElement(['Dodoma', 'Arusha', 'Mwanza', 'Kilimanjaro', 'Tanga']),
            'crop_type' => fake()->randomElement(['Maize', 'Coffee', 'Cassava', 'Cotton', 'Cashews']),
            'status' => fake()->randomElement(['Scheduled', 'Sent']),
        ];
    }
}
