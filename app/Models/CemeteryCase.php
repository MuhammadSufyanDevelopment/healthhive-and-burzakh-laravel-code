<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CemeteryCase extends Model
{
    use HasFactory;
    protected $fillable = [
        'case_name',
        'user_id',
        'grave_number',
        'mortician_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(BurzakhMember::class, 'user_id');
    }

    public function caseDetails()
    {
        return $this->hasMany(BurzakhMemberDocumentSubmission::class, 'name_of_deceased', 'case_name');
    }

    public function mortician()
    {
        return $this->belongsTo(Mortician::class, 'mortician_id');
    }

    public function mancipalityRecord()
    {
        return $this->hasOne(BurzakhUserSubmissionToMancipality::class, 'case_name', 'case_name');
    }

}
