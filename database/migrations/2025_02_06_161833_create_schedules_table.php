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
        Schema::create('schedules', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('product_id')->constrained();
            $table->integer('recurres_in')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('weekly_schedules', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('schedule_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->tinyInteger('is_default')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('schedule_days', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('weekly_schedule_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('day_of_week')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('schedule_breaks', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name')->nullable();
            $table->foreignUlid('schedule_day_id')->constrained()->onDelete('cascade');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_breaks');
        Schema::dropIfExists('schedule_days');
        Schema::dropIfExists('weekly_schedules');
        Schema::dropIfExists('schedules');
    }
};
