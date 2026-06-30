<?php

namespace App\Models\EmailMarketing;

use Illuminate\Database\Eloquent\Model;

class EmailEvent extends Model
{
    public $timestamps = false;

    protected $fillable = ['message_id', 'event_type', 'url', 'ip', 'user_agent', 'payload', 'created_at'];

    protected $casts = [
        'payload' => 'array',
        'created_at' => 'datetime',
    ];

    public function message()
    {
        return $this->belongsTo(EmailMessage::class, 'message_id');
    }
}
