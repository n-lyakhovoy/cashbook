<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'type',
        'message',
        'user_id',
        'is_email',
        'is_push',
        'is_sent',
    ];

    protected $casts = [
        'is_email' => 'boolean',
        'is_push' => 'boolean',
        'is_sent' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
