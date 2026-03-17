<?php

namespace Database\Factories;

use App\Models\Farmer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Farmer>
 */
class FarmerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'phone_number' => fake()->phoneNumber(),
            'country' => 'Tanzania',
            'region' => fake()->randomElement(['Dodoma', 'Arusha', 'Mwanza', 'Kilimanjaro', 'Tanga']),
            'crop_type' => fake()->randomElement(['Maize', 'Coffee', 'Cassava', 'Cotton', 'Cashews']),
            'language' => fake()->randomElement(['Swahili', 'English', 'Sukuma', 'Chaga']),
        ];
    }
}
