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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('voucher_type_id')->constrained()->onDelete('cascade');
            $table->foreignUlid('host_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();

            // Value and quantity information
            $table->decimal('value', 10, 2)->nullable();
            $table->integer('pay_for_quantity')->nullable();
            $table->integer('get_quantity')->nullable();

            // Feature flags
            $table->boolean('is_transferrable')->default(false);
            $table->boolean('is_gift_eligible')->default(false);
            $table->boolean('can_combine')->default(false);

            // Inventory and status
            $table->integer('inventory_limit')->nullable();
            $table->integer('sold_count')->default(0);
            $table->tinyInteger('status')->default(1); // 1 = active

            // Dates
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Create pivot table for voucher products
        Schema::create('product_voucher', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('product_id')->constrained()->onDelete('cascade');
            $table->foreignUlid('voucher_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Ensure product-voucher combination is unique
            $table->unique(['product_id', 'voucher_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_voucher');
        Schema::dropIfExists('vouchers');
    }
};
