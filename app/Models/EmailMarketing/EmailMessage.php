<?php

namespace App\Models\EmailMarketing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EmailMessage extends Model
{
    protected $fillable = [
        'campaign_id', 'provider_id', 'contact_id', 'tracking_token', 'to_email', 'to_name',
        'subject', 'html_body', 'text_body', 'status', 'provider_message_id',
        'error_message', 'sent_at', 'delivered_at', 'opened_at', 'clicked_at',
        'bounced_at', 'complained_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'opened_at' => 'datetime',
        'clicked_at' => 'datetime',
        'bounced_at' => 'datetime',
        'complained_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (EmailMessage $message) {
            $message->tracking_token ??= (string) Str::uuid();
        });
    }

    public function campaign()
    {
        return $this->belongsTo(EmailCampaign::class, 'campaign_id');
    }

    public function provider()
    {
        return $this->belongsTo(EmailProvider::class, 'provider_id');
    }

    public function contact()
    {
        return $this->belongsTo(EmailContact::class, 'contact_id');
    }

    public function events()
    {
        return $this->hasMany(EmailEvent::class, 'message_id');
    }
}
