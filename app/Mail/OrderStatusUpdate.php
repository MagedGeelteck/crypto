<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdate extends Mailable
{
    use Queueable, SerializesModels;

    public $deposit;
    public $sells;
    public $totalAmount;
    public $statusText;
    public $statusColor;

    /**
     * Create a new message instance.
     */
    public function __construct($deposit, $sells, $totalAmount, $statusText, $statusColor)
    {
        $this->deposit = $deposit;
        $this->sells = $sells;
        $this->totalAmount = $totalAmount;
        $this->statusText = $statusText;
        $this->statusColor = $statusColor;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        // Get email from multiple sources with guaranteed fallback
        $fromEmail = config('mail.from.address') ?? env('MAIL_FROM_ADDRESS') ?? 'noreply@' . (parse_url(config('app.url'), PHP_URL_HOST) ?? 'localhost');
        $fromName = config('mail.from.name') ?? env('MAIL_FROM_NAME') ?? gs('site_name') ?? 'CryptoOnion';
        
        // Ensure email is never null
        if (empty($fromEmail) || !filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
            $fromEmail = 'noreply@cryptoonion.com';
        }
        
        return new Envelope(
            subject: 'Order Status Update - #' . $this->deposit->code,
            replyTo: [$fromEmail],
            from: new \Illuminate\Mail\Mailables\Address($fromEmail, $fromName),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order_status_update',
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
