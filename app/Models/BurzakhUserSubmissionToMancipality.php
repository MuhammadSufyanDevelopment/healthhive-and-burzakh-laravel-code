<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BurzakhUserSubmissionToMancipality extends Model
{
    use HasFactory;
    protected $fillable = [
        'burial_place',
        'burial_timing' ,
        'preferred_cemetery',
        'user_id',
        'case_name',
        'status',
        'grave_number',
        'religion',
        'sect',
        'special_request',
        'grave_status'
    ];

    public function user()
    {
        return $this->belongsTo(BurzakhMember::class, 'user_id');
    }
    
    public function caseDetails()
    {
        return $this->hasMany(BurzakhMemberDocumentSubmission::class, 'name_of_deceased', 'case_name');
    }
}
