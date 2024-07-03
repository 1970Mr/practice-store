<?php

use App\Enums\PaymentMethod;
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
//            $table->char('internal_code ', 32)->index();
            $table->string('internal_code')->unique()->comment('Our system creates');
            $table->string('transaction_id')->nullable()->comment('The payment gateway creates');
            $table->integer('amount');
            $table->enum('payment_method', PaymentMethod::values());
            $table->string('gateway')->nullable();
            $table->text('callback_payload')->nullable();
            $table->bigInteger('reference_id')->nullable();
            $table->enum('status', Status::values());
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
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
