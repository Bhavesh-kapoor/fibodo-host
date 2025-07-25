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
        Schema::create('transactions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('client_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUlid('host_id')->nullable()->constrained('users')->nullOnDelete();

            $table->unsignedInteger('transaction_type')->default(0); // 0 = refund, 1 = payment
            $table->unsignedInteger('transaction_status')->default(0); // 0 = pending, 1 = completed, 2 = failed
            $table->ulid('payment_method_id')->nullable(); // 
            $table->string('transaction_id')->nullable(); // Transaction ID from payment gateway
            $table->string('payment_gateway')->nullable(); // Payment gateway used
            $table->string('payment_gateway_response')->nullable(); // Response from payment gateway
            $table->string('payment_gateway_reference')->nullable(); // Reference from payment gateway
            $table->string('payment_gateway_status')->nullable(); // Status from payment gateway
            $table->string('payment_gateway_error')->nullable(); // Error message from payment gateway

            $table->decimal('amount', 10, 2); // Amount paid or refunded
            $table->text('notes')->nullable();

            $table->timestamp('paid_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
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
