<?php

use App\Enums\Status;
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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
//            $table->char('payment_id', 32)->index();
            $table->string('payment_id')->comment('Our system creates');
            $table->string('transaction_id')->nullable()->comment('The payment gateway creates');
            $table->integer('amount');
            $table->bigInteger('reference_id')->nullable();
            $table->enum('status', Status::items());
            $table->morphs('product');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
