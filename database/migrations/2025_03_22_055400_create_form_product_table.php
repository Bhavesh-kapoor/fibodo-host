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
        Schema::create('form_product', function (Blueprint $table) {

            $table->foreignUlid('product_id')->constrained();
            $table->foreignUlid('form_id')->constrained();

            $table->primary(['product_id', 'form_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_product');
    }
};
