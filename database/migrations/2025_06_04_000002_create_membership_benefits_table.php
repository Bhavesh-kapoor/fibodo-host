<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_benefits', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('type'); // video_access, live_stream, class_passes, private_session, booking_discount, advance_booking
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // Create pivot table for membership plan benefits
        Schema::create('membership_plan_benefits', function (Blueprint $table) {

            $table->foreignUlid('membership_plan_id')->constrained('membership_plans')->cascadeOnDelete();
            $table->foreignUlid('membership_benefit_id')->constrained('membership_benefits')->cascadeOnDelete();

            // Additional fields for specific benefit types
            $table->boolean('is_unlimited')->nullable(); // For class passes
            $table->integer('pass_count')->nullable(); // For class passes
            $table->decimal('discount_percentage', 5, 2)->nullable(); // For booking discount
            $table->integer('advance_booking_days')->nullable(); // For advance booking

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_plan_benefits');
        Schema::dropIfExists('membership_benefits');
    }
};
