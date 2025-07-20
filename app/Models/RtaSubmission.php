<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RtaSubmission extends Model
{
    use HasFactory;
    protected $fillable=[
        'mourning_start_date',
        'time',
        'location_of_house',
        'signs_required',
        'custom_text_for_sign',
        'user_id',
        'status',
        'rejection_reason',
        'mourning_end_date',
        'case_name'
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
