<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SupportTicketReply extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $message;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct($ticket, $message, $user)
    {
        $this->ticket = $ticket;
        $this->message = $message;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        // Get email from multiple sources with guaranteed fallback
        $fromEmail = config('mail.from.address') ?? env('MAIL_FROM_ADDRESS') ?? 'noreply@' . (parse_url(config('app.url'), PHP_URL_HOST) ?? 'localhost');
        
        // Ensure email is never null
        if (empty($fromEmail) || !filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
            $fromEmail = 'noreply@cryptoonion.com';
        }
        
        return new Envelope(
            subject: 'New Reply to Your Support Ticket #' . $this->ticket->ticket,
            replyTo: [$fromEmail],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.support_ticket_reply',
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
