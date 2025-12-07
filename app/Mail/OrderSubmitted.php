<?php

namespace App\Mail;

use App\Models\Deposit;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $deposit;

    /**
     * Create a new message instance.
     */
    public function __construct(Deposit $deposit)
    {
        $this->deposit = $deposit;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('New Order Submitted - Order #' . $this->deposit->code)
                    ->view('emails.order_submitted');
    }
}
