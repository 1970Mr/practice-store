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
            'usage_limit' => fake()->optional()->numberBetween(1, 10),
            'used_count' => 0,
            'start_date' => fake()->dateTimeBetween('-1 week', '+1 week'),
            'end_date' => fake()->dateTimeBetween('+1 week', '+1 month')
        ];
    }
}
