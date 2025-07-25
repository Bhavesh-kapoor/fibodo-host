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
        Schema::create('settings', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('user_id')->nullable()->constrained('users');
            $table->string('setting_key'); // unique by user_id;
            $table->text('setting_value');
            $table->string('setting_type')->default('string'); // SettingType::class
            $table->string('setting_group')->default('general');
            $table->string('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->tinyInteger('status')->default(1); // 1: active, 0: inactive


            $table->index(['user_id', 'setting_key'], 'user_id_setting_key_index');
            $table->index('setting_group', 'setting_group_index');
            $table->index('status', 'status_index');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
