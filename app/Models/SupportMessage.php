<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportMessage extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'admin_type',
        'message',
        'role'
    ];

    public function user()
    {
        return $this->belongsTo(BurzakhMember::class);
    }
}
