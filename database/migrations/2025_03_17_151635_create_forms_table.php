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

        Schema::create('forms', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string("title");
            $table->foreignUlid("form_type_id")->nullable();
            $table->boolean("status")->default(0);
            $table->timestamps();
        });

        Schema::create('form_types', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string("title");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forms');
        Schema::dropIfExists('form_types');
    }
};
