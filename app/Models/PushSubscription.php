<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PushSubscription extends Model
{
    protected $fillable = [
        'user_client_id',
        'endpoint',
        'public_key',
        'auth_token',
        'content_encoding',
    ];

    public function userClient(): BelongsTo
    {
        return $this->belongsTo(UserClient::class);
    }
}
