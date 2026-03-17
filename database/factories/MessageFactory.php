<?php

namespace Database\Factories;

use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content' => fake()->sentence(15),
            'recipient_group' => fake()->randomElement(['Maize Farmers', 'Arusha Region', 'Swahili Speakers']),
            'sent_at' => fake()->dateTimeBetween('-1 month', 'now'),
            'status' => fake()->randomElement(['Delivered', 'Failed', 'Pending']),
        ];
    }
}
