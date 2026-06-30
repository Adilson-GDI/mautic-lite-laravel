<?php

namespace App\Models\EmailMarketing;

use Illuminate\Database\Eloquent\Model;

class EmailContact extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'document', 'status', 'source', 'metadata'];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function lists()
    {
        return $this->belongsToMany(EmailList::class, 'email_contact_list', 'contact_id', 'list_id')->withPivot('created_at');
    }

    public function messages()
    {
        return $this->hasMany(EmailMessage::class, 'contact_id');
    }

    public function canReceiveEmail(): bool
    {
        return $this->status === 'active';
    }
}
