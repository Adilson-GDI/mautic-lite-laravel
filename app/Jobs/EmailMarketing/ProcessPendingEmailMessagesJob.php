<?php

namespace App\Jobs\EmailMarketing;

use App\Jobs\EmailMarketing\SendEmailMessageJob;
use App\Models\EmailMarketing\EmailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessPendingEmailMessagesJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly int $limit = 100)
    {
    }

    public function handle(): void
    {
        EmailMessage::query()
            ->where('status', 'pending')
            ->oldest()
            ->limit($this->limit)
            ->pluck('id')
            ->each(fn ($id) => SendEmailMessageJob::dispatch($id));
    }
}
