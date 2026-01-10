<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
        'is_internal_note',
        'working_time',
        'status_changed_from',
        'status_changed_to',
        'priority_changed_from',
        'priority_changed_to',
        'assigned_from',
        'assigned_to',
    ];

    protected $casts = [
        'is_internal_note' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class);
    }

    /**
     * Check if this is a system message (status change, assignment, etc.)
     */
    public function isSystemMessage(): bool
    {
        return $this->status_changed_from 
            || $this->status_changed_to 
            || $this->priority_changed_from 
            || $this->priority_changed_to 
            || $this->assigned_from 
            || $this->assigned_to;
    }

    /**
     * Get reply type (customer, staff, internal, system)
     */
    public function getType(): string
    {
        if ($this->isSystemMessage()) {
            return 'system';
        }

        if ($this->is_internal_note) {
            return 'internal';
        }

        $user = $this->user;
        if ($user->hasRole('administrator') || $user->hasRole('agent')) {
            return 'staff';
        }

        return 'customer';
    }
}
