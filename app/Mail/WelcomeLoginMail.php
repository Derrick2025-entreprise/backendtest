<?php

namespace App\Mail;

use App\Models\Personnel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeLoginMail extends Mailable
{
    use Queueable, SerializesModels;

    public $personnel;
    public $loginTime;
    public $ipAddress;

    /**
     * Create a new message instance.
     */
    public function __construct(Personnel $personnel, string $loginTime, string $ipAddress)
    {
        $this->personnel = $personnel;
        $this->loginTime = $loginTime;
        $this->ipAddress = $ipAddress;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bienvenue - Connexion réussie',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome-login',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
