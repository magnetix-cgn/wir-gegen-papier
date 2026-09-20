<?php

namespace App\Mail;

use App\Models\Supporter;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SupporterUnsubscribeMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Supporter $supporter,
        public string $unsubscribeUrl,
        public string $expiresAtText,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Unterstützung für Wir gegen Papier widerrufen',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.supporters.unsubscribe',
            text: 'emails.supporters.unsubscribe-text',
        );
    }
}
