<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthCareJob extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
    */
    protected $fillable = [
        'job_title',
        'brand',
        'job_type',
        'applier_message_title',
        'applier_message_description',
        'job_description',
        'recruiter_id',
        'experience',
        'graduation_year',
        'status',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'job_title' => 'string',
        'brand' => 'string',
        'applier_message_title' => 'string',
        'applier_message_description' => 'string',
        'job_description' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
