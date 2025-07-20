<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ambulance extends Model
{
    use HasFactory;
    protected $fillable = [
        'driver_name',
        'vehicle_number',
        'current_location',
        'contact_number',
        'cemetry_name',
        'status',
        'user_id',
    ];

    public function dispatchInfo()
    {
        return $this->hasMany(DispatchAmbulance::class, 'vehicle_number', 'vehicle_number');
    }

    public function caseDetail()
    {
        return $this->hasOne(BurzakhMemberDocumentSubmission::class, 'name_of_deceased', 'case_name');
    }
}
