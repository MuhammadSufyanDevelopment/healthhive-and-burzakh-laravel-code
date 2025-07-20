<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BurzakhMemberDocumentSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'death_notification_file',
        'hospital_certificate',
        'death_notification_file_status',
        'passport_or_emirate_id_front',
        'hospital_certificate_status',
        'passport_or_emirate_id_front_status',
        'passport_or_emirate_id_back_status',
        'passport_or_emirate_id_back',
        'user_id',
        'resting_place',
        'case_status',
        'police_clearance',
        'release_form',
        'additional_document',
        'send_notification_message',
        'admin_id',
        'name_of_deceased',
        'date_of_death',
        'cause_of_death',
        'location',
        'additional_document_upload_user',
        'burial_submission_status',
        'ratio',
        'ambulance_dispatched',
        'gender',
        'age',
        'passport_document',
        'passport_document_status'
    ];

    public function user()
    {
        return $this->belongsTo(BurzakhMember::class, 'user_id');
    }
}
