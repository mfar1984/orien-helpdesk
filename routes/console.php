<?php

use App\Models\ActivityLog;
use App\Models\AuditLog;
use App\Models\Ticket;
use App\Models\TicketStatus;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Clean up old audit logs every hour
Schedule::call(function () {
    AuditLog::cleanup();
})->hourly()->name('audit-log-cleanup')->withoutOverlapping();

// Clean up activity logs when exceeding 3MB every hour
Schedule::call(function () {
    ActivityLog::cleanup();
})->hourly()->name('activity-log-cleanup')->withoutOverlapping();

// Auto-close inactive tickets daily
Schedule::call(function () {
    $autoCloseDays = (int) setting('ticket_auto_close_days', 7);
    $closedStatus = TicketStatus::where('slug', 'closed')->first();
    
    if ($closedStatus) {
        $ticketsToClose = Ticket::whereNull('closed_at')
            ->where('updated_at', '<', now()->subDays($autoCloseDays))
            ->whereHas('status', function ($q) {
                $q->whereNotIn('slug', ['closed', 'resolved']);
            })
            ->get();
        
        foreach ($ticketsToClose as $ticket) {
            $ticket->update([
                'status_id' => $closedStatus->id,
                'closed_at' => now(),
            ]);
            
            ActivityLog::log(
                'status_change',
                'tickets',
                "Ticket #{$ticket->id} auto-closed after {$autoCloseDays} days of inactivity",
                $ticket
            );
        }
    }
})->daily()->name('auto-close-tickets')->withoutOverlapping();

// Command to manually clean up audit logs
Artisan::command('audit:cleanup', function () {
    $beforeCount = AuditLog::count();
    AuditLog::cleanup();
    $afterCount = AuditLog::count();
    $deleted = $beforeCount - $afterCount;
    
    $this->info("Audit log cleanup completed. Deleted {$deleted} old records.");
    $this->info("Current table size: " . AuditLog::getTableSizeFormatted());
})->purpose('Clean up old audit logs (older than 3 days or exceeding 3MB)');

// Command to manually clean up activity logs
Artisan::command('activity:cleanup', function () {
    $beforeCount = ActivityLog::count();
    ActivityLog::cleanup();
    $afterCount = ActivityLog::count();
    $deleted = $beforeCount - $afterCount;
    
    $this->info("Activity log cleanup completed. Deleted {$deleted} old records.");
    $this->info("Current table size: " . ActivityLog::getTableSizeFormatted());
})->purpose('Clean up activity logs when exceeding 3MB');

// Command to manually auto-close tickets
Artisan::command('tickets:auto-close', function () {
    $autoCloseDays = (int) setting('ticket_auto_close_days', 7);
    $closedStatus = TicketStatus::where('slug', 'closed')->first();
    
    if (!$closedStatus) {
        $this->error('Closed status not found.');
        return;
    }
    
    $ticketsToClose = Ticket::whereNull('closed_at')
        ->where('updated_at', '<', now()->subDays($autoCloseDays))
        ->whereHas('status', function ($q) {
            $q->whereNotIn('slug', ['closed', 'resolved']);
        })
        ->get();
    
    $count = 0;
    foreach ($ticketsToClose as $ticket) {
        $ticket->update([
            'status_id' => $closedStatus->id,
            'closed_at' => now(),
        ]);
        
        ActivityLog::log(
            'status_change',
            'tickets',
            "Ticket #{$ticket->id} auto-closed after {$autoCloseDays} days of inactivity",
            $ticket
        );
        $count++;
    }
    
    $this->info("Auto-closed {$count} tickets that were inactive for {$autoCloseDays}+ days.");
})->purpose('Auto-close tickets that have been inactive for the configured number of days');
