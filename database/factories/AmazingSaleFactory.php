<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AmazingSale>
 */
class AmazingSaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'percent' => fake()->numberBetween(5, 70),
            'usage_limit' => fake()->optional()->numberBetween(1, 10),
            'used_count' => 0,
            'start_time' => fake()->dateTimeBetween('-1 week', '+1 week'),
            'end_time' => fake()->dateTimeBetween('+1 week', '+1 month'),
            'product_id' => Product::factory()
        ];
    }
}
