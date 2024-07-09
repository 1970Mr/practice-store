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
        Schema::create('amazing_sales', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('percent');
            $table->integer('usage_limit')->nullable()->comment('If it was null, it means no limit');
            $table->integer('used_count')->default(0);
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amazing_sale');
    }
};
