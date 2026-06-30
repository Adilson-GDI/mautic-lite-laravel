<?php

namespace App\Services\EmailMarketing;

use App\Models\EmailMarketing\EmailProvider;

class EmailRateLimiterService
{
    public function canSend(EmailProvider $provider): bool
    {
        return $this->remainingDaily($provider) > 0 && $this->remainingHourly($provider) > 0;
    }

    public function remainingDaily(EmailProvider $provider): int
    {
        if ($provider->daily_limit <= 0) {
            return PHP_INT_MAX;
        }

        $sent = $provider->messages()
            ->whereIn('status', ['sent', 'delivered', 'opened', 'clicked'])
            ->where('sent_at', '>=', now()->subDay())
            ->count();

        return max(0, $provider->daily_limit - $sent);
    }

    public function remainingHourly(EmailProvider $provider): int
    {
        if ($provider->hourly_limit <= 0) {
            return PHP_INT_MAX;
        }

        $sent = $provider->messages()
            ->whereIn('status', ['sent', 'delivered', 'opened', 'clicked'])
            ->where('sent_at', '>=', now()->subHour())
            ->count();

        return max(0, $provider->hourly_limit - $sent);
    }
}
