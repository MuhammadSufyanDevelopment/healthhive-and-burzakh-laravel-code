<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BurzakhNotification extends Model
{
    use HasFactory;
    protected $fillable = [
        'notification_message',
        'user_id',
        'role',
    ];
}
