<?php

namespace App\Mail;

use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketReplied extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $reply;

    /**
     * Create a new message instance.
     */
    public function __construct(Ticket $ticket, TicketReply $reply)
    {
        $this->ticket = $ticket;
        $this->reply = $reply;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('New Reply on Ticket: #' . $this->ticket->ticket_number)
            ->view('emails.template')
            ->with([
                'badge' => 'New Reply',
                'badgeColor' => '#3b82f6',
                'ticketNumber' => $this->ticket->ticket_number,
                'title' => $this->ticket->subject,
                'greeting' => 'Dear <strong>' . $this->ticket->creator->name . '</strong>, you have a new reply from <strong>' . $this->reply->user->name . '</strong>',
                'infoItems' => [
                    'Reply From' => '👤 ' . $this->reply->user->name,
                    'Date' => $this->reply->created_at->format('d M Y, H:i'),
                    'Status' => $this->ticket->status->name ?? 'Open',
                ],
                'messageContent' => nl2br(e($this->reply->message)),
                'actionUrl' => route('tickets.show', $this->ticket->id),
                'actionText' => 'View Full Conversation',
            ]);
    }
}
