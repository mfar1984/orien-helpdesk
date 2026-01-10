<?php

namespace App\Mail;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketAssigned extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $agent;

    /**
     * Create a new message instance.
     */
    public function __construct(Ticket $ticket, User $agent)
    {
        $this->ticket = $ticket;
        $this->agent = $agent;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('New Ticket Assigned: #' . $this->ticket->ticket_number)
            ->view('emails.template')
            ->with([
                'badge' => 'Assigned',
                'badgeColor' => '#8b5cf6',
                'ticketNumber' => $this->ticket->ticket_number,
                'title' => $this->ticket->subject,
                'greeting' => 'Hello <strong>' . $this->agent->name . '</strong>, a new ticket has been assigned to you.',
                'infoItems' => [
                    'Customer' => $this->ticket->creator->name,
                    'Email' => $this->ticket->creator->email,
                    'Priority' => $this->ticket->priority->name ?? 'Normal',
                    'Category' => $this->ticket->category->name ?? 'General',
                    'SLA Response' => $this->ticket->slaRule ? $this->ticket->slaRule->response_time_hours . ' hours' : 'N/A',
                ],
                'messageContent' => nl2br(e($this->ticket->description)),
                'actionUrl' => route('tickets.show', $this->ticket->id),
                'actionText' => 'View & Respond to Ticket',
            ]);
    }
}
