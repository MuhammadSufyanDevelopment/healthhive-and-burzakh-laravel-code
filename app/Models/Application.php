<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\HealthCareJob;

class Application extends Model
{
    use HasFactory;

    protected $fillable=[
        'job_id',
        'full_name',
        'phone_number',
        'email_address',
        'university',
        'student_id',
        'resume',
        'experience',
        'graduation_date',
        'healthcare_worker_id',
        'recruiter_id',
        'cover_letter',
    ];

    protected $cast=[
        'job_id'=>'integer',
        'full_name'=>'string',
        'phone_number'=>'integer',
        'email_address'=>'string',
        'university'=>'string',
        'student_id'=>'integer',
        'resume'=>'string',
        'experience'=>'float',
        'graduation_date'=>'string',
        'healthcare_worker_id'=>'integer',
        'recruiter_id'=>'integer',
        'cover_letter'=>'string',
    ];


    public function job(): BelongsTo
    {
        return $this->belongsTo(HealthCareJob::class, 'job_id');
    }

}
