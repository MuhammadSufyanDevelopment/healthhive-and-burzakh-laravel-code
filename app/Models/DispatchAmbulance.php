<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DispatchAmbulance extends Model
{
    use HasFactory;
    protected $fillable = [
        'case_name',
        'standby_mosque',
        'additional_notes',
        'status',
        'user_id',
        'vehicle_number'
    ];

    public function caseDetails()
    {
        return $this->hasMany(BurzakhMemberDocumentSubmission::class, 'name_of_deceased', 'case_name');
    }

    public function municipalitySubmission()
    {
        return $this->hasOne(BurzakhUserSubmissionToMancipality::class, 'case_name', 'case_name');
    }
}
