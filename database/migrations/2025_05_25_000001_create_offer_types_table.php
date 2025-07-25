<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offer_types', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->boolean('requires_account')->default(false);
            $table->integer('default_expiry_days')->nullable();
            $table->json('settings')->nullable();
            $table->boolean('status')->default(true)->comment('1: active, 0: inactive');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offer_types');
    }
};
