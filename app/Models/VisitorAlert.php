<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorAlert extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'  ,
        'gender',
        'alert_time',
        'cemetery_location',
        'mosque_name',
        'description',
        'description_arabic',
        'status',
        'make_as_important',
    ];
}
