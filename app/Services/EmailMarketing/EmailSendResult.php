<?php

namespace App\Services\EmailMarketing;

class EmailSendResult
{
    public function __construct(
        public readonly bool $success,
        public readonly ?string $providerMessageId = null,
        public readonly ?string $errorMessage = null,
    ) {
    }

    public static function sent(?string $providerMessageId = null): self
    {
        return new self(true, $providerMessageId);
    }

    public static function failed(string $errorMessage): self
    {
        return new self(false, null, $errorMessage);
    }
}
