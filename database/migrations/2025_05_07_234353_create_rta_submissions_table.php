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
        Schema::create('rta_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('mourning_start_date');
            $table->string('time');
            $table->string('location_of_house');
            $table->string('signs_required');
            $table->string('custom_text_for_sign');
            $table->string('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rta_submissions');
    }
};
