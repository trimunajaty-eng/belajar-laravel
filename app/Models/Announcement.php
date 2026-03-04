<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'content',
        'type',
        'meeting_date',
        'is_active'
    ];

    protected $casts = [
        'meeting_date' => 'datetime',
        'is_active' => 'boolean',
    ];
}