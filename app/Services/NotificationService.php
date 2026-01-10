<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use App\Mail\TicketCreated;
use App\Mail\TicketAssigned;
use App\Mail\TicketReplied;
use App\Mail\TicketStatusChanged;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Check if a notification type is enabled.
     */
    private static function isEnabled(string $settingKey): bool
    {
        return (bool) setting($settingKey, true);
    }

    /**
     * Send ticket created notification.
     */
    public static function sendTicketCreated(Ticket $ticket): bool
    {
        // Check if notification is enabled
        if (!self::isEnabled('email_ticket_created')) {
            return false;
        }

        try {
            return EmailService::send(
                new TicketCreated($ticket),
                $ticket->creator->email
            );
        } catch (\Exception $e) {
            Log::error('Failed to send ticket created notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send ticket assigned notification.
     */
    public static function sendTicketAssigned(Ticket $ticket, User $agent): bool
    {
        // Check if notification is enabled
        if (!self::isEnabled('email_ticket_assigned')) {
            return false;
        }

        try {
            return EmailService::send(
                new TicketAssigned($ticket, $agent),
                $agent->email
            );
        } catch (\Exception $e) {
            Log::error('Failed to send ticket assigned notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send ticket reply notification.
     */
    public static function sendTicketReply(Ticket $ticket, TicketReply $reply): bool
    {
        // Check if notification is enabled
        if (!self::isEnabled('email_ticket_replied')) {
            return false;
        }

        try {
            // Don't send for internal notes
            if ($reply->is_internal_note) {
                return false;
            }

            $sent = false;

            // Notify ticket creator if reply is from agent/admin
            if ($reply->user_id !== $ticket->created_by) {
                $sent = EmailService::send(
                    new TicketReplied($ticket, $reply),
                    $ticket->creator->email
                );
            }

            // Notify assigned agent if reply is from customer
            if ($ticket->assigned_to && $reply->user_id === $ticket->created_by) {
                $sent = EmailService::send(
                    new TicketReplied($ticket, $reply),
                    $ticket->assignee->email
                );
            }

            return $sent;
        } catch (\Exception $e) {
            Log::error('Failed to send ticket reply notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send ticket status changed notification.
     */
    public static function sendTicketStatusChanged(Ticket $ticket, string $oldStatus, string $newStatus): bool
    {
        // Check if notification is enabled
        if (!self::isEnabled('email_ticket_status_changed')) {
            return false;
        }

        try {
            return EmailService::send(
                new TicketStatusChanged($ticket, $oldStatus, $newStatus),
                $ticket->creator->email
            );
        } catch (\Exception $e) {
            Log::error('Failed to send ticket status changed notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send welcome email to new user.
     */
    public static function sendWelcomeEmail(User $user): bool
    {
        // Check if notification is enabled
        if (!self::isEnabled('email_user_created')) {
            return false;
        }

        try {
            return EmailService::send(
                new WelcomeEmail($user),
                $user->email
            );
        } catch (\Exception $e) {
            Log::error('Failed to send welcome email: ' . $e->getMessage());
            return false;
        }
    }
}
