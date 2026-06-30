<?php

namespace App\Jobs\EmailMarketing;

use App\Models\EmailMarketing\EmailEvent;
use App\Models\EmailMarketing\EmailMessage;
use App\Services\EmailMarketing\EmailRateLimiterService;
use App\Services\EmailMarketing\EmailSenderFactory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendEmailMessageJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(public readonly int $messageId)
    {
    }

    public function handle(EmailSenderFactory $factory, EmailRateLimiterService $limiter): void
    {
        $message = EmailMessage::with(['provider', 'contact', 'campaign'])->findOrFail($this->messageId);

        if ($message->status !== 'pending') {
            return;
        }

        $provider = $message->provider;
        $contact = $message->contact;
        $campaign = $message->campaign;

        if (! $provider->active || ! $contact->canReceiveEmail() || ($campaign && ! $campaign->canDispatch())) {
            $message->update(['status' => 'canceled', 'error_message' => 'Envio bloqueado por regra de provedor, contato ou campanha.']);
            return;
        }

        if (! $limiter->canSend($provider)) {
            return;
        }

        $message->update(['status' => 'processing']);

        $result = $factory->make($provider)->send($message);

        if ($result->success) {
            $message->update([
                'status' => 'sent',
                'provider_message_id' => $result->providerMessageId,
                'sent_at' => now(),
                'error_message' => null,
            ]);
            EmailEvent::create(['message_id' => $message->id, 'event_type' => 'sent', 'created_at' => now()]);
            return;
        }

        $message->update(['status' => 'failed', 'error_message' => $result->errorMessage]);
        EmailEvent::create([
            'message_id' => $message->id,
            'event_type' => 'failed',
            'payload' => ['error' => $result->errorMessage],
            'created_at' => now(),
        ]);
    }
}
