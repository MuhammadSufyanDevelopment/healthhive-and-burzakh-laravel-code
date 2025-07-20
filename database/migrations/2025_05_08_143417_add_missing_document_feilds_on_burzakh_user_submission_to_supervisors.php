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
            $table->string('death_notification')->nullable()->after('loved_one_city');
            $table->string('hospital_certificate')->nullable()->after('death_notification');
            $table->string('passport_or_emirate_id')->nullable()->after('hospital_certificate');
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
