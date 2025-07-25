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
        Schema::create('otps', function (Blueprint $table) {
            $table->ulid('id')->primary()->unique();
            $table->string('otp');
            $table->string('email');
            $table->integer('attempts')->default(0);
            $table->smallInteger('source')->nullable()->comment('1: login, 2: reset password, 3: Signup OTP');
            $table->foreignUlid('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamp('expires_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otps');
    }
};
