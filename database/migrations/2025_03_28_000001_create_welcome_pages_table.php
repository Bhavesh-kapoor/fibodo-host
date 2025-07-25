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
        Schema::create('welcome_pages', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('app_id')->index(); // e.g., 'fibodo', 'host', 'default'
            $table->string('title');
            $table->text('text');
            $table->integer('sort_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('welcome_pages');
    }
}; 