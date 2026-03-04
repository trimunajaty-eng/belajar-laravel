<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkSetting extends Model
{
    protected $fillable = [
        'work_start_time',
        'work_end_time',
        'late_threshold'
    ];

    protected $casts = [
        'work_start_time' => 'datetime:H:i',
        'work_end_time' => 'datetime:H:i',
        'late_threshold' => 'datetime:H:i',
    ];
}