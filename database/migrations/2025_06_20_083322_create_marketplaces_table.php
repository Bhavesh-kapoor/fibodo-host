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
        Schema::create('marketplaces', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('code')->unique();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('tagline')->nullable();
            $table->text('description')->nullable();
            $table->text('excerpt')->nullable();
            $table->string('logo')->nullable();
            $table->string('address')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('website_url')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('x_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('tiktok_url')->nullable();
            $table->tinyInteger('status')->default(1);

            $table->softDeletes();
            $table->timestamps();
        });

        // marketplace trust
        Schema::create('marketplace_trust', function (Blueprint $table) {

            $table->ulid('id')->primary();

            // foreign keys
            $table->foreignUlid('marketplace_id')->constrained('marketplaces');
            $table->foreignUlid('trust_id')->constrained('trusts');

            // unique constraints
            $table->unique(['marketplace_id', 'trust_id']);

            // timestamps
            $table->softDeletes();
            $table->timestamps();
        });

        // marketplace subscribers
        Schema::create('marketplace_subscribers', function (Blueprint $table) {
            $table->ulid('id')->primary();

            // foreign keys
            $table->foreignUlid('user_id')->constrained('users');
            $table->foreignUlid('trust_id')->constrained('trusts')->nullable();
            $table->foreignUlid('marketplace_id')->constrained('marketplaces');

            // unique constraints
            $table->unique(['user_id', 'marketplace_id', 'trust_id']);

            // status
            $table->tinyInteger('status')->default(1);

            // timestamps
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketplace_subscribers');
        Schema::dropIfExists('marketplace_trust');
        Schema::dropIfExists('marketplaces');
    }
};
