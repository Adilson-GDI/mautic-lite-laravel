<?php

namespace App\Models\EmailMarketing;

use Illuminate\Database\Eloquent\Model;

class EmailList extends Model
{
    protected $fillable = ['name', 'description', 'active'];

    protected $casts = ['active' => 'boolean'];

    public function contacts()
    {
        return $this->belongsToMany(EmailContact::class, 'email_contact_list', 'list_id', 'contact_id')->withPivot('created_at');
    }

    public function campaigns()
    {
        return $this->belongsToMany(EmailCampaign::class, 'email_campaign_lists', 'list_id', 'campaign_id')->withPivot('created_at');
    }
}
