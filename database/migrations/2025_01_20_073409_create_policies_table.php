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
        Schema::create('policies', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('title');
            $table->longText('description')->nullable();
            $table->string('policy_type')->nullable();
            $table->foreignUlid('user_id')->nullable()->constrained(); // host or null for system policies
            $table->boolean('is_global')->default(true);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('policies');
    }
};
