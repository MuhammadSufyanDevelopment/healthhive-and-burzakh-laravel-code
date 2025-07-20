<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPosting extends Model
{
    use HasFactory;

    protected $fillable=[
        'company',
        'job_type',
        'job_description',
        'recruiter_id',
        'experience',
        'graduation_year',
        'created_at',
        'updated_at',

    ];

    protected $casts = [
        'id' => 'integer',
        'company' => 'string',
        'job_description' => 'string',
        'healthcare_worker_id'=>'integer',
        'recruiter_id'=>'integer',
        'experience'=>'integer',
        'graduation_year'=> 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
