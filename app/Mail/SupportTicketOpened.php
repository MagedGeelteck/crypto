<?php

namespace App\Mail;

use App\Models\SupportTicket;
use App\Models\SupportMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SupportTicketOpened extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $messageObj;

    /**
     * Create a new message instance.
     */
    public function __construct(SupportTicket $ticket, SupportMessage $message)
    {
        $this->ticket = $ticket;
        $this->messageObj = $message;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject("New Support Ticket: #{$this->ticket->ticket} - {$this->ticket->subject}")
            ->view('emails.support_ticket_opened')
            ->with([
                'ticket' => $this->ticket,
                'message' => $this->messageObj,
            ]);
    }
}
