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
        Schema::create('cards', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('merchant_ref', 50); //we are generating this to share with worldnet
            $table->string('worldnet_ref')->nullable(); //this is given by worldnet is storing card details
            $table->string('number', 20);
            $table->string('type', 20);
            $table->string('expiry', 4);
            $table->string('holder_name')->nullable();
            $table->string('holder_email')->nullable();
            $table->string('holder_phone')->nullable();
            $table->tinyInteger('is_stored')->default(0)->unsigned();
            $table->boolean('is_default')->default(false);
            $table->json('description');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
