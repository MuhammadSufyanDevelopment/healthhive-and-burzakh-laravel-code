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
        Schema::create('burzakh_user_submission_to_supervisors', function (Blueprint $table) {
            $table->id();
            $table->string('burial_timing');
            $table->string('preferred_commentary');
            $table->string('loved_one_city');
            $table->string('police_clearence_certificate');
            $table->string('user_id');
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('burzakh_user_submission_to_supervisors');
    }
};
