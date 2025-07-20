<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\HealthCareJob;

class HealthCareJobsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HealthCareJob::insert([
            [
                'job_title' => 'Nurse Practitioner',
                'brand' => 'MediCare',
                'applier_message_title' => 'Join Our Team',
                'applier_message_description' => 'We are looking for dedicated healthcare workers.',
                'job_description' => 'Provide high-quality patient care in a fast-paced environment.',
                'job_type' => 'healthCareWorker',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'job_title' => 'Registered Nurse',
                'brand' => 'HealthPlus',
                'applier_message_title' => 'Be a Part of Excellence',
                'applier_message_description' => 'Help us make a difference in patient care.',
                'job_description' => 'Assist in patient care and documentation tasks.',
                'job_type' => 'healthCareWorker',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'job_title' => 'Medical Assistant',
                'brand' => 'Wellness Center',
                'applier_message_title' => 'Exciting Opportunities Await',
                'applier_message_description' => 'Contribute to a team focused on patient wellness.',
                'job_description' => 'Support medical staff with administrative and clinical duties.',
                'job_type' => 'healthCareWorker',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        //
    }
}
