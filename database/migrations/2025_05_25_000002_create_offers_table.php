<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('offer_type_id')->constrained()->onDelete('cascade');
            $table->foreignUlid('host_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();

            // Offer value and type
            $table->decimal('value', 10, 2)->nullable();
            $table->boolean('is_discount')->default(true); // true for discount, false for fixed price

            // Target audience
            $table->tinyInteger('target_audience')->default(1); // 1 = All Attendees, 2 = Lead Broker, 3 = New Clients

            // Product targeting
            $table->boolean('apply_to_all_products')->default(false);

            // Terms and conditions
            $table->text('terms_conditions')->nullable();

            // Status and dates
            $table->tinyInteger('status')->default(1); // 1 = active
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Create pivot table for offer products
        Schema::create('offer_product', function (Blueprint $table) {
            $table->foreignUlid('offer_id')->constrained()->onDelete('cascade');
            $table->foreignUlid('product_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Ensure offer-product combination is unique
            $table->unique(['offer_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offer_product');
        Schema::dropIfExists('offers');
    }
};
