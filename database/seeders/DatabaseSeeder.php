<?php

namespace Database\Seeders;

use App\Models\Coupon;
use App\Models\Product;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@gmail.com',
        ]);
        Coupon::factory(5)->create([
            'usage_limit' => fake()->numberBetween(1, 5),
            'used_count' => 0,
            'couponable_id' => $user->id,
            'couponable_type' => User::class,
        ]);

        $products = Product::factory(50)->create();
        foreach ($products as $product) {
            if (fake()->boolean(30)) {
                Coupon::factory()->create([
                    'couponable_id' => $product->id,
                    'couponable_type' => Product::class,
                ]);
            }
        }
    }
}
