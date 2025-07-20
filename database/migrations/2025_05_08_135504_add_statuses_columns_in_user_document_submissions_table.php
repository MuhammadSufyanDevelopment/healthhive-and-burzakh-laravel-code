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
        Schema::table('burzakh_member_document_submissions', function (Blueprint $table) {
            $table->string('death_notification_file_status')->nullable()->default('pending')->after('death_notification_file');
            $table->string('hospital_certificate_status')->nullable()->default('pending')->after('hospital_certificate');
            $table->string('passport_or_emirate_id_front_status')->nullable()->default('pending')->after('passport_or_emirate_id_front');
            $table->string('passport_or_emirate_id_back_status')->nullable()->default('pending')->after('passport_or_emirate_id_back');
            $table->string('case_status')->nullable()->default('pending')->after('passport_or_emirate_id_back_status');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('burzakh_member_document_submissions', function (Blueprint $table) {
            //
        });
    }
};
