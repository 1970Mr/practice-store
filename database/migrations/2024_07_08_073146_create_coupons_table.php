<?php

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
            $table->tinyInteger('percent');
            $table->integer('amount_limit');
            $table->integer('usage_limit')->nullable()->comment('If it was null, it means no limit');
            $table->integer('used_count')->default(0);
            $table->timestamp('expire_time');
            $table->morphs('couponable');
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
