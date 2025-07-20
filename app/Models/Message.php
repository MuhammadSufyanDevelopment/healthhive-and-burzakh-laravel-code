<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable=[
        'title',
        'description',
        'healthcare_worker_id',
        'recruiter_id',
        'student_id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'healthcare_worker_id'=>'integer',
        'recruiter_id'=>'integer',
        'student_id'=>'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
