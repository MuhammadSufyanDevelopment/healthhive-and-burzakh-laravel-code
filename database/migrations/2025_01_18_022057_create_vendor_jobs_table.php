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
        Schema::create('vendor_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('product_image');
            $table->string('product_name');
            $table->string('product_details');
            $table->string('category');
            $table->string('price');
            $table->string('discounted_price');
            $table->string('vendor_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_jobs');
    }
};
