<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ticket_number',
        'created_by',
        'assigned_to',
        'subject',
        'description',
        'category_id',
        'priority_id',
        'status_id',
        'sla_rule_id',
        'due_date',
        'resolved_at',
        'closed_at',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    /**
     * Boot method for auto-generating ticket number
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($ticket) {
            if (!$ticket->ticket_number) {
                $year = date('Y');
                $lastTicket = static::withTrashed()
                    ->where('ticket_number', 'like', "TKT-{$year}-%")
                    ->orderBy('id', 'desc')
                    ->first();
                
                $number = $lastTicket ? intval(substr($lastTicket->ticket_number, -4)) + 1 : 1;
                $ticket->ticket_number = sprintf('TKT-%s-%04d', $year, $number);
            }
        });
    }

    /**
     * Relationships
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Multiple assignees relationship (Many-to-Many)
     */
    public function assignees()
    {
        return $this->belongsToMany(User::class, 'ticket_assignments', 'ticket_id', 'user_id')
            ->withPivot('assigned_at')
            ->withTimestamps();
    }

    public function category()
    {
        return $this->belongsTo(TicketCategory::class, 'category_id');
    }

    public function priority()
    {
        return $this->belongsTo(TicketPriority::class, 'priority_id');
    }

    public function status()
    {
        return $this->belongsTo(TicketStatus::class, 'status_id');
    }

    public function slaRule()
    {
        return $this->belongsTo(SlaRule::class, 'sla_rule_id');
    }

    public function replies()
    {
        return $this->hasMany(TicketReply::class)->orderBy('created_at', 'asc');
    }

    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class);
    }

    /**
     * Permission checks
     */
    public function canBeViewedBy(User $user): bool
    {
        // Admin can view all
        if ($user->hasPermission('tickets.view') && $user->isAdmin()) {
            return true;
        }
        
        // Agent can view assigned tickets
        if ($user->isAgent() && $this->assignees->contains('id', $user->id)) {
            return true;
        }
        
        // Customer can only view their own tickets
        if ($user->isCustomer() && $user->id === $this->created_by) {
            return true;
        }
        
        // Check general permission
        return $user->hasPermission('tickets.view') || $user->hasPermission('tickets_view');
    }

    public function canBeEditedBy(User $user): bool
    {
        // Agent CANNOT edit tickets - only view and reply
        if ($user->isAgent()) {
            return false;
        }
        
        // Admin can edit all tickets
        if ($user->isAdmin()) {
            return true;
        }
        
        // Customer (owner) can edit their own tickets
        if ($user->isCustomer() && $user->id === $this->created_by) {
            return true;
        }
        
        return false;
    }

    public function canBeDeletedBy(User $user): bool
    {
        // Check permission first
        if (!$user->hasPermission('tickets.delete') && !$user->hasPermission('tickets_delete')) {
            return false;
        }
        
        // Admin can delete all
        if ($user->isAdmin()) {
            return true;
        }
        
        // Customer can delete their own tickets
        return $user->id === $this->created_by;
    }

    public function canUpdateStatusAndAssign(User $user): bool
    {
        // Only users with tickets.manage permission can update status/priority/assign
        // This includes Administrators with manage permission
        // Customers should NOT be able to update status/priority even if they own the ticket
        return $user->hasPermission('tickets.manage');
    }

    /**
     * Check if ticket is overdue
     */
    public function isOverdue(): bool
    {
        if (!$this->due_date) {
            return false;
        }

        $closedStatuses = ['Resolved', 'Closed'];
        if (in_array($this->status->name, $closedStatuses)) {
            return false;
        }

        return $this->due_date->isPast();
    }

    /**
     * Check if ticket is due soon (within 24 hours)
     */
    public function isDueSoon(): bool
    {
        if (!$this->due_date) {
            return false;
        }

        $closedStatuses = ['Resolved', 'Closed'];
        if (in_array($this->status->name, $closedStatuses)) {
            return false;
        }

        return $this->due_date->isFuture() && $this->due_date->diffInHours(now()) <= 24;
    }
}
