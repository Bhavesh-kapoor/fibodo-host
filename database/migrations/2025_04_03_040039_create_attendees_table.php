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
        Schema::create('attendees', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('activity_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('client_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignUlid('host_id')->constrained('users')->nullOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('mobile_number')->nullable();
            $table->boolean('is_lead_attendee')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendees');
    }
};
