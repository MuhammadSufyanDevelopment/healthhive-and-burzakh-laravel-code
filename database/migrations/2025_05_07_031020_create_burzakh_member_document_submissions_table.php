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
        Schema::create('burzakh_member_document_submissions', function (Blueprint $table) {
            $table->id();
            'death_notification_file';
            'hospital_certificate';
            'passport_or_emirate_id_front';
            'passport_or_emirate_id_back';
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('burzakh_member_document_submissions');
    }
};
