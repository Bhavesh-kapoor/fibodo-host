<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string("title")->nullable();
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->integer("duration")->nullable();
            $table->integer("recurres_in")->nullable(); // will help later to convert back to activity when breakTime is deleted
            $table->text("note")->nullable();
            $table->tinyInteger('is_time_off')->default(0);
            $table->tinyInteger('is_break')->default(0);
            $table->foreignUlid('user_id')->constrained();
            $table->foreignUlid('product_id')->nullable();
            $table->foreignUlid('schedule_id')->nullable();
            $table->foreignUlid('schedule_day_id')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->integer('seats_booked')->default(0);
            $table->integer('seats_available')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
