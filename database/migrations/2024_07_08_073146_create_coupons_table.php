<?php

use App\Enums\CouponType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->integer('amount');
            $table->enum('amount_type', CouponType::values());
            $table->integer('minimum_amount')->nullable()->comment('If it was null, it means no limit');
            $table->integer('discount_ceiling')->nullable()->comment('If it was null, it means no limit');
            $table->integer('usage_limit')->nullable()->comment('If it was null, it means no limit');
            $table->integer('used_count')->default(0);
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->foreignId('user_id')->nullable()->comment('If it was null, it means this is for all users')
                ->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
