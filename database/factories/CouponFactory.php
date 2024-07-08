<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coupon>
 */
class CouponFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => Str::upper(Str::random(10)),
            'percent' => fake()->numberBetween(5, 50),
            'amount_limit' => fake()->numberBetween(1000, 10000),
            'expire_time' => fake()->dateTimeBetween('now', '+1 year'),
            'usage_limit' => null,
            'used_count' => 0,
            'couponable_id' => null,
            'couponable_type' => null,
        ];
    }
}
