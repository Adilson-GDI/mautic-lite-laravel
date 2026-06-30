<?php

namespace App\Services\EmailMarketing;

class AwsSesEmailSender extends SmtpEmailSender
{
    // Estrutura preparada para trocar o transporte SMTP por SDK/API do SES sem afetar os jobs.
}
