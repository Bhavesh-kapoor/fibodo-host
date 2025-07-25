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
        Schema::create('users', function (Blueprint $table) {
            $table->ulid('id')->primary()->unique();
            $table->string('code', 10)->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();
            $table->string('username')->unique()->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('country_code', 3)->nullable();
            $table->string('mobile_number')->unique()->nullable();
            $table->string('gender', 10)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->longText('notes')->nullable();

            $table->string('last_login_ip')->nullable();
            $table->string('last_login_user_agent')->nullable();
            $table->rememberToken();

            # nhs
            $table->string('nhs_id')->nullable();
            // invited by
            $table->foreignUlid('invited_by')->nullable();

            $table->tinyInteger('accept_terms')->default(1);
            $table->tinyInteger('is_temp_password')->default(0);
            $table->tinyInteger('status')->default(1);


            # date
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('card_verified_at')->nullable();
            $table->timestamp('temp_password_expired_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('invited_at')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignUlid('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
