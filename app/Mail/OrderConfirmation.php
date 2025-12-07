<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $deposit;
    public $orders;
    public $totalAmount;
    public $btcAmount;

    /**
     * Create a new message instance.
     */
    public function __construct($deposit, $orders, $totalAmount, $btcAmount)
    {
        $this->deposit = $deposit;
        $this->orders = $orders;
        $this->totalAmount = $totalAmount;
        $this->btcAmount = $btcAmount;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Confirmation - #' . $this->deposit->code,
            replyTo: [env('MAIL_FROM_ADDRESS')],
            from: new \Illuminate\Mail\Mailables\Address(
                env('MAIL_FROM_ADDRESS'),
                env('MAIL_FROM_NAME', gs('site_name'))
            ),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order_confirmation',
            text: 'emails.order_confirmation_text',
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
