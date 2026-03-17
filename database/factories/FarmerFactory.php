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
            'full_name' => $this->faker->name(),
            'phone_number' => '+255'.$this->faker->numerify('#########'),
            'region' => $this->faker->randomElement(['Arusha', 'Kilimanjaro', 'Dodoma', 'Mwanza', 'Tanga']),
            'village' => $this->faker->city(),
            'preferred_language' => $this->faker->randomElement(['English', 'Swahili']),
            'wants_sms' => true,
        ];
    }
}
