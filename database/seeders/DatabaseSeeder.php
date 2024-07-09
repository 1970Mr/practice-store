<?php

namespace Database\Seeders;

use App\Models\AmazingSale;
use App\Models\CommonDiscount;
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

        Coupon::factory(3)->create(['user_id' => $user]);
        Coupon::factory(10)->create();

        $products = Product::factory(50)->create();
        foreach ($products as $product) {
            if (fake()->boolean(30)) {
                AmazingSale::factory()->create([
                    'product_id' => $product,
                ]);
            }
        }

        CommonDiscount::factory(10)->create();
    }
}
