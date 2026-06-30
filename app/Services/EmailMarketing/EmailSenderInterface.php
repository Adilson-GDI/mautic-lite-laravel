<?php

namespace App\Services\EmailMarketing;

use App\Models\EmailMarketing\EmailMessage;

interface EmailSenderInterface
{
    public function send(EmailMessage $message): EmailSendResult;
}
