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
        Schema::create('common_discounts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->tinyInteger('percent');
            $table->integer('minimum_amount')->nullable()->comment('If it was null, it means no limit');
            $table->integer('discount_ceiling')->nullable()->comment('If it was null, it means no limit');
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('common_discounts');
    }
};
