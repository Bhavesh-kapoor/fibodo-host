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
        Schema::create('hosts', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('business_name')->nullable();
            $table->string('business_tagline')->nullable();
            $table->longText('business_about')->nullable();
            $table->string('business_profile_slug')->nullable();
            $table->string('business_website')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_address_line1')->nullable();
            $table->string('company_address_line2')->nullable();
            $table->string('company_city')->nullable();
            $table->string('company_zip')->nullable();
            $table->string('company_country')->nullable();
            $table->string('company_contact_no')->nullable();
            $table->string('company_email')->nullable();
            $table->string('company_vat')->nullable();
            $table->string('company_website')->nullable();
            $table->integer('profile_state')->default(0)->comment('1 to 5 = 1: Basic, 2: Account, 3: Business, 4: Company, 5: Completed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hosts');
    }
};
