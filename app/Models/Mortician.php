<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mortician extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'case_name',
        'user_id',
        'phone_number',
        'status',
    ];

    public function caseDetails()
    {
        return $this->hasMany(BurzakhMemberDocumentSubmission::class, 'name_of_deceased', 'case_name');
    }

    public function cemeteryCases()
    {
        return $this->hasMany(CemeteryCase::class, 'mortician_id', 'id');
    }
}
