<?php

namespace App\Models\EmailMarketing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EmailUnsubscribe extends Model
{
    protected $fillable = ['contact_id', 'email', 'campaign_id', 'reason', 'token', 'unsubscribed_at'];

    protected $casts = ['unsubscribed_at' => 'datetime'];

    protected static function booted(): void
    {
        static::creating(function (EmailUnsubscribe $unsubscribe) {
            $unsubscribe->token ??= (string) Str::uuid();
            $unsubscribe->unsubscribed_at ??= now();
        });
    }
}
