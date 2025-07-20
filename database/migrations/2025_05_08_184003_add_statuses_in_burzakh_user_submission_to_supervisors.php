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
        Schema::table('burzakh_user_submission_to_supervisors', function (Blueprint $table) {
            //
            $table->string('passport_status')->default('pending')->nullable()->after('loved_one_city');
            $table->string('death_notification_status')->default('pending')->nullable()->after('passport_status');
            $table->string('hospital_certificate_status')->default('pending')->nullable()->after('death_notification_status');
            $table->string('police_clearence_certificate_status')->default('pending')->nullable()->after('hospital_certificate_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('burzakh_user_submission_to_supervisors', function (Blueprint $table) {
            //
        });
    }
};
