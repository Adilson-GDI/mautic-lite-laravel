<?php

namespace App\Services\EmailMarketing;

use App\Models\EmailMarketing\EmailProvider;
use InvalidArgumentException;

class EmailSenderFactory
{
    public function __construct(private readonly EmailContentRenderer $renderer)
    {
    }

    public function make(EmailProvider $provider): EmailSenderInterface
    {
        return match ($provider->type) {
            'google_workspace' => new GoogleWorkspaceEmailSender($this->renderer),
            'aws_ses' => new AwsSesEmailSender($this->renderer),
            default => throw new InvalidArgumentException('Tipo de provedor nao suportado.'),
        };
    }
}
