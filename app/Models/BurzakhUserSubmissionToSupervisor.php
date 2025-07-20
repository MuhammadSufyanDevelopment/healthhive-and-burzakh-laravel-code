<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BurzakhUserSubmissionToSupervisor extends Model
{
    use HasFactory;
    protected $fillable = [
        'burial_timing',
        'preferred_commentary',
        'loved_one_city',
        'police_clearence_certificate',
        'death_notification' ,
        'hospital_certificate' ,
        'passport_or_emirate_id_of_deceased',
        'user_id',
        'status',
        'case_id'
    ];
}
