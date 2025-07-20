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
        Schema::create('health_care_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('job_title');
            $table->string('brand');
            $table->string('applier_message_title');
            $table->string('applier_message_description');
            $table->string('job_description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_care_jobs');
    }
};
