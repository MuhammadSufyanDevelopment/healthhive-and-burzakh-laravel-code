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
        Schema::create('video_meet_timings', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('date');
            $table->string('time');
            $table->string('admin_id');
            $table->string('meeting_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_meet_timings');
    }
};
