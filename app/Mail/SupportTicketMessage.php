<?php

namespace App\Mail;

use App\Models\SupportTicket;
use App\Models\SupportMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SupportTicketMessage extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $messageObj;

    public function __construct(SupportTicket $ticket, SupportMessage $message)
    {
        $this->ticket = $ticket;
        $this->messageObj = $message;
    }

    public function build()
    {
        return $this->subject("New Message on Ticket: #{$this->ticket->ticket} - {$this->ticket->subject}")
            ->view('emails.support_ticket_message')
            ->with([
                'ticket' => $this->ticket,
                'message' => $this->messageObj,
            ]);
    }
}
