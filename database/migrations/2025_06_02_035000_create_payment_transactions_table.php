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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('card_id')->constrained('cards')->cascadeOnDelete();
            $table->foreignUlid('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->string('order_id');
            $table->decimal('amount')->nullable();
            $table->string('response_code');
            $table->string('unique_ref');
            $table->string('datetime');
            $table->json('description');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
