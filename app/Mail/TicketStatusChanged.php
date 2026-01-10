<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $oldStatus;
    public $newStatus;

    /**
     * Create a new message instance.
     */
    public function __construct(Ticket $ticket, string $oldStatus, string $newStatus)
    {
        $this->ticket = $ticket;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        // Determine badge color based on new status
        $badgeColor = match(strtolower($this->newStatus)) {
            'closed' => '#6b7280',
            'resolved' => '#10b981',
            'in progress' => '#f59e0b',
            'pending' => '#ef4444',
            default => '#3b82f6',
        };

        return $this->subject('Ticket Status Updated: #' . $this->ticket->ticket_number)
            ->view('emails.template')
            ->with([
                'badge' => 'Status Update',
                'badgeColor' => $badgeColor,
                'ticketNumber' => $this->ticket->ticket_number,
                'title' => $this->ticket->subject,
                'greeting' => 'Dear <strong>' . $this->ticket->creator->name . '</strong>, your ticket status has been updated.',
                'infoItems' => [
                    'Previous Status' => $this->oldStatus,
                    'New Status' => '✨ ' . $this->newStatus,
                    'Updated' => now()->format('d M Y, H:i'),
                ],
                'content' => '<p style="color: #6b7280;">Please login to view the latest updates on your ticket.</p>',
                'actionUrl' => route('tickets.show', $this->ticket->id),
                'actionText' => 'View Ticket Details',
            ]);
    }
}
