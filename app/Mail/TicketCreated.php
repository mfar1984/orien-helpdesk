<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;

    /**
     * Create a new message instance.
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Ticket Created: #' . $this->ticket->ticket_number)
            ->view('emails.template')
            ->with([
                'badge' => 'New Ticket',
                'badgeColor' => '#10b981',
                'ticketNumber' => $this->ticket->ticket_number,
                'title' => $this->ticket->subject,
                'greeting' => 'Dear <strong>' . $this->ticket->creator->name . '</strong>, your support ticket has been created successfully.',
                'infoItems' => [
                    'Category' => $this->ticket->category->name ?? 'General',
                    'Priority' => $this->ticket->priority->name ?? 'Normal',
                    'Status' => $this->ticket->status->name ?? 'Open',
                    'Created' => $this->ticket->created_at->format('d M Y, H:i'),
                ],
                'messageContent' => nl2br(e($this->ticket->description)),
                'actionUrl' => route('tickets.show', $this->ticket->id),
                'actionText' => 'View Ticket Details',
            ]);
    }
}
