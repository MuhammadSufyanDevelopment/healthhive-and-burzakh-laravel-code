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
            $table->string('case_id')->nullable()->after('user_id');
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
