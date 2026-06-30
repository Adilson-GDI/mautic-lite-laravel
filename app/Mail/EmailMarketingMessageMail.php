<?php

namespace App\Mail;

use App\Models\EmailMarketing\EmailMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailMarketingMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(private readonly EmailMessage $message)
    {
    }

    public function envelope(): Envelope
    {
        $provider = $this->message->provider;

        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address($provider->from_email, $provider->from_name),
            replyTo: $provider->reply_to ? [new \Illuminate\Mail\Mailables\Address($provider->reply_to)] : [],
            subject: $this->message->subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'email-marketing.mail.html',
            text: $this->message->text_body ? 'email-marketing.mail.text' : null,
            with: ['emailMessage' => $this->message],
        );
    }
}
