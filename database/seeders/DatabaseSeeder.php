<?php

namespace Database\Seeders;

use App\Models\MembershipPlan;
use App\Models\Product;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@gmail.com',
        ]);

        Product::factory(50)->create();

        $plans = [
            ['name' => 'یک ماهه', 'duration' => 30, 'price' => 10000],
            ['name' => 'سه ماهه', 'duration' => 90, 'price' => 25000],
            ['name' => 'شش ماهه', 'duration' => 180, 'price' => 45000],
            ['name' => 'یک ساله', 'duration' => 365, 'price' => 80000],
            ['name' => 'برای همیشه', 'duration' => 0, 'price' => 150000],
        ];

        foreach ($plans as $plan) {
            MembershipPlan::create($plan);
        }
    }
}
