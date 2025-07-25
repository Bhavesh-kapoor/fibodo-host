<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_plans', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('host_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('type'); // peak, off_peak
            $table->string('plan_type'); // individual, family
            $table->text('description')->nullable();

            // Family plan specific fields
            $table->integer('junior_count')->nullable();
            $table->integer('adult_count')->nullable();
            $table->integer('senior_count')->nullable();
            $table->boolean('unlimited_junior')->default(false);

            // Individual plan specific fields
            $table->string('individual_plan_type')->nullable(); // normal, pay_as_you_go

            // Cost & Billing
            $table->decimal('joining_fee', 10, 2)->nullable();
            $table->string('billing_period'); // monthly, yearly
            $table->decimal('amount', 10, 2);
            $table->json('payment_types'); // Array of payment types

            // Terms & Conditions
            $table->integer('renewal_day')->nullable(); // 1-31, e.g., 1st of every month
            $table->integer('grace_period_days')->nullable();
            $table->integer('cancellation_period_days')->nullable();
            $table->boolean('is_transferable')->default(false);
            $table->boolean('can_pause')->default(false);

            $table->integer('status')->default(1); // 0: draft, 1: published, 2: archived
            $table->timestamp('published_at')->nullable();
            $table->timestamp('archived_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_plans');
    }
};
