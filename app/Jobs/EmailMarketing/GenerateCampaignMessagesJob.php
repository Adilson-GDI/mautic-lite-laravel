<?php

namespace App\Jobs\EmailMarketing;

use App\Models\EmailMarketing\EmailCampaign;
use App\Models\EmailMarketing\EmailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Str;

class GenerateCampaignMessagesJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly int $campaignId)
    {
    }

    public function handle(): void
    {
        $campaign = EmailCampaign::with(['lists.contacts', 'provider'])->findOrFail($this->campaignId);

        if (! in_array($campaign->status, ['scheduled', 'sending'], true)) {
            return;
        }

        $campaign->lists
            ->flatMap(fn ($list) => $list->contacts)
            ->unique('id')
            ->filter(fn ($contact) => $contact->canReceiveEmail() && filter_var($contact->email, FILTER_VALIDATE_EMAIL))
            ->each(function ($contact) use ($campaign) {
                EmailMessage::firstOrCreate(
                    ['campaign_id' => $campaign->id, 'contact_id' => $contact->id],
                    [
                        'provider_id' => $campaign->provider_id,
                        'tracking_token' => (string) Str::uuid(),
                        'to_email' => $contact->email,
                        'to_name' => $contact->name,
                        'subject' => $campaign->subject,
                        'html_body' => $campaign->html_body,
                        'text_body' => $campaign->text_body,
                        'status' => 'pending',
                    ],
                );
            });

        $campaign->update(['status' => 'sending', 'started_at' => $campaign->started_at ?? now()]);
    }
}
