<?php

namespace Database\Factories;

use App\Models\User;
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
        // 0: percent, 1: fixed amount
        $amountType = fake()->randomElement([0, 1]);
        $amount = fake()->numberBetween(5, 70);
        if ($amountType === 1) {
            $amount = fake()->numberBetween(50000, 500000);
        }
        return [
            'code' => Str::upper(Str::random(10)),
//            'code' => fake()->unique()->bothify('COUPON-######'),
            'amount' => $amount,
            'amount_type' => $amountType,
            'minimum_amount' => fake()->optional()->numberBetween(100000, 1000000),
            'discount_ceiling' => fake()->optional()->numberBetween(50000, 500000),
            'usage_limit' => fake()->optional()->numberBetween(1, 10),
            'used_count' => 0,
            'start_time' => fake()->dateTimeBetween('-1 week', '+1 week'),
            'end_time' => fake()->dateTimeBetween('+1 week', '+1 month'),
            'user_id' => User::factory(),
        ];
    }
}
