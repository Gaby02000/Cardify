<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnownLogin extends Model
{
    protected $fillable = [
        'notifiable_type',
        'notifiable_id',
        'fingerprint',
        'ip',
        'user_agent',
        'last_seen_at',
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
    ];
}
