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
        Schema::create('products', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('title');
            $table->string('sub_title')->nullable();
            $table->longText('description')->nullable();
            $table->unsignedInteger('kcal_burn')->nullable();
            $table->foreignUlid('user_id')->nullable()->constrained();
            $table->foreignId('category_id')->nullable()->constrained('categories');
            $table->foreignId('sub_category_id')->nullable()->constrained('categories');
            $table->foreignUlid('activity_type_id')->nullable()->constrained('activity_types');
            $table->foreignUlid('product_type_id')->nullable()->contrained('product_types');

            // session_duration
            $table->integer('session_duration')->nullable();

            // attendee settings 
            $table->string('ability_level', 20)->nullable();
            $table->tinyInteger('has_age_restriction')->default(0);
            $table->smallInteger('age_below')->nullable();
            $table->smallInteger('age_above')->nullable();
            $table->string('gender_restrictions', 20)->nullable();
            $table->tinyInteger('is_family_friendly')->default(0)->nullable();

            #Acknowledgement Forms
            $table->text('form_type_ids')->nullable();

            // price settings
            $table->smallInteger('no_of_slots')->nullable();
            $table->decimal('price')->nullable();
            $table->tinyInteger('is_age_sensitive')->nullable();
            $table->decimal('junior_price')->nullable();
            $table->decimal('adult_price')->nullable();
            $table->decimal('senior_price')->nullable();
            $table->tinyInteger('is_walk_in_pricing')->nullable();
            $table->decimal('walk_in_price')->nullable();
            $table->tinyInteger('is_walk_in_age_sensitive')->nullable();
            $table->decimal('walk_in_junior_price')->nullable();
            $table->decimal('walk_in_adult_price')->nullable();
            $table->decimal('walk_in_senior_price')->nullable();
            $table->tinyInteger('is_special_pricing')->nullable();
            $table->decimal('multi_attendee_price')->nullable();
            $table->decimal('all_space_price')->nullable();
            # refund polices attached
            $table->text('refund_policy_ids')->nullable();

            // location settings 
            $table->string('address')->nullable();
            $table->longText('note')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();

            $table->tinyInteger('status')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('archived_at')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
