<?php

namespace App\Models\EmailMarketing;

use Illuminate\Database\Eloquent\Model;

class EmailCampaign extends Model
{
    protected $fillable = [
        'name', 'provider_id', 'subject', 'preheader', 'html_body', 'text_body',
        'status', 'scheduled_at', 'started_at', 'finished_at',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function provider()
    {
        return $this->belongsTo(EmailProvider::class, 'provider_id');
    }

    public function lists()
    {
        return $this->belongsToMany(EmailList::class, 'email_campaign_lists', 'campaign_id', 'list_id')->withPivot('created_at');
    }

    public function messages()
    {
        return $this->hasMany(EmailMessage::class, 'campaign_id');
    }

    public function canDispatch(): bool
    {
        return in_array($this->status, ['scheduled', 'sending'], true);
    }
}
