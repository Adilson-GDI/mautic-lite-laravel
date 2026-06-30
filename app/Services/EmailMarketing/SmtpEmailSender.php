<?php

namespace App\Services\EmailMarketing;

use App\Mail\EmailMarketingMessageMail;
use App\Models\EmailMarketing\EmailMessage;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SmtpEmailSender implements EmailSenderInterface
{
    public function __construct(private readonly EmailContentRenderer $renderer)
    {
    }

    public function send(EmailMessage $message): EmailSendResult
    {
        try {
            $provider = $message->provider;

            config([
                'mail.mailers.email_provider' => [
                    'transport' => 'smtp',
                    'host' => $provider->smtp_host,
                    'port' => $provider->smtp_port,
                    'encryption' => $provider->smtp_encryption,
                    'username' => $provider->smtp_username,
                    'password' => $provider->smtp_password_plain,
                    'timeout' => 30,
                ],
            ]);

            app('mail.manager')->forgetMailers();
            $this->renderer->prepare($message);

            Mail::mailer('email_provider')
                ->to($message->to_email, $message->to_name)
                ->send(new EmailMarketingMessageMail($message));

            return EmailSendResult::sent();
        } catch (Throwable $exception) {
            return EmailSendResult::failed($exception->getMessage());
        }
    }
}
