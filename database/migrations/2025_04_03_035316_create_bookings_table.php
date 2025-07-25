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
        Schema::create('bookings', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('host_id')->constrained('users')->nullOnDelete();
            $table->foreignUlid('activity_id');
            $table->foreignUlid('product_id');

            $table->string('booking_number')->unique()->nullable();

            $table->decimal('price_per_seat', 10, 2); // Activity price 
            $table->unsignedInteger('seats_booked')->default(1);
            $table->decimal('discount_amount', 10, 2)->default(0); // Discount amount
            $table->decimal('sub_total', 10, 2); // Price after discount
            $table->decimal('tax_amount', 10, 2)->default(0); // Tax amount
            $table->decimal('total_amount', 10, 2); // Total amount after tax

            $table->string("product_title")->nullable();
            $table->string("product_type")->nullable();
            $table->timestamp('activity_start_time');
            $table->timestamp('activity_end_time');
            $table->text('notes')->nullable();
            $table->unsignedTinyInteger('is_walk_in')->default(0);

            $table->unsignedInteger('status')->default(1); // 0 = pending, 1 = completed/confirmed, 2 = cancelled
            $table->timestamp('payment_due_at')->nullable(); // 1 hour timer
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_note')->nullable();

            $table->unsignedInteger('payment_status')->default(0); // 0 = pending, 1 = paid, 2 = refunded
            $table->ulid('payment_method_id')->nullable(); // 

            $table->foreignUlid('created_by')->constrained('users')->cascadeOnDelete(); // bookedBy
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
