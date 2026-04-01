<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'type',
        'meeting_date',
        'is_active',
        'expired_at',
    ];

    protected $casts = [
        'meeting_date' => 'datetime',
        'expired_at'   => 'datetime',
        'is_active'    => 'boolean',
    ];

    public function isExpired(): bool
    {
        return $this->expired_at !== null && $this->expired_at->isPast();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where(function ($q) {
                         $q->whereNull('expired_at')
                           ->orWhere('expired_at', '>', now());
                     });
    }
}
