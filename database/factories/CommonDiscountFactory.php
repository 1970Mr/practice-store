<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CommonDiscount>
 */
class CommonDiscountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence,
            'percent' => fake()->numberBetween(5, 70),
            'minimum_amount' => fake()->optional()->numberBetween(100000, 1000000),
            'discount_ceiling' => fake()->optional()->numberBetween(50000, 500000),
            'start_time' => fake()->dateTimeBetween('-1 week', '+1 week'),
            'end_time' => fake()->dateTimeBetween('+1 week', '+1 month')
        ];
    }
}
