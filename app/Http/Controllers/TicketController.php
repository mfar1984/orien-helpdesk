<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Models\TicketCategory;
use App\Models\TicketPriority;
use App\Models\TicketReply;
use App\Models\TicketStatus;
use App\Models\SlaRule;
use App\Models\User;
use App\Services\ActivityLogService;
use App\Services\NotificationService;
use App\Services\ContentFilterService;
use App\Services\SpamCheckService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    /**
     * Display tickets listing with tabs.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Check permission
        if (!$user->hasPermission('tickets.view')) {
            abort(403, 'You do not have permission to view tickets.');
        }
        
        $tab = $request->get('tab', 'open');
        
        $query = Ticket::with(['creator', 'assignee', 'assignees', 'category', 'priority', 'status']);
        
        // ROLE-BASED FILTERING
        if ($user->hasRole('customer')) {
            // Customers see only their own tickets (ISOLATED)
            $query->where('created_by', $user->id);
        } elseif ($user->hasRole('agent')) {
            // Agents see only tickets assigned to them (via many-to-many relationship)
            $query->whereHas('assignees', function($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        }
        // Administrators see ALL tickets (NO ISOLATION)
        
        // TAB FILTERING
        switch ($tab) {
            case 'open':
                $query->whereHas('status', fn($q) => $q->where('name', 'Open'));
                break;
            case 'assigned-to-me':
                // Tickets assigned to current user (via many-to-many)
                $query->whereHas('assignees', fn($q) => $q->where('users.id', $user->id));
                break;
            case 'assigned-to-others':
                // Tickets assigned to others (has assignees but not current user)
                $query->whereHas('assignees')
                      ->whereDoesntHave('assignees', fn($q) => $q->where('users.id', $user->id));
                break;
            case 'unassigned':
                // Tickets with no assignees
                $query->whereDoesntHave('assignees');
                break;
            case 'due-soon':
                $closedStatusIds = TicketStatus::whereIn('name', ['Resolved', 'Closed'])->pluck('id');
                $query->whereBetween('due_date', [now(), now()->addHours(24)])
                      ->whereNotIn('status_id', $closedStatusIds);
                break;
            case 'overdue':
                $closedStatusIds = TicketStatus::whereIn('name', ['Resolved', 'Closed'])->pluck('id');
                $query->where('due_date', '<', now())
                      ->whereNotIn('status_id', $closedStatusIds);
                break;
        }
        
        // SEARCH & FILTERS
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }
        
        if ($priorityId = $request->get('priority')) {
            $query->where('priority_id', $priorityId);
        }
        
        if ($statusId = $request->get('status')) {
            $query->where('status_id', $statusId);
        }
        
        $perPage = (int) setting('pagination_size', 15);
        $tickets = $query->orderBy('created_at', 'desc')->paginate($perPage);
        
        // Calculate tab counts
        $counts = $this->calculateTabCounts($user);
        
        $priorities = TicketPriority::where('status', 'active')->orderBy('sort_order')->get();
        $statuses = TicketStatus::where('status', 'active')->orderBy('sort_order')->get();
        
        return view('tickets.index', [
            'currentTab' => $tab,
            'tabs' => [
                'open' => 'Open Tickets',
                'assigned-to-me' => 'Assigned to Me',
                'assigned-to-others' => 'Assigned to Others',
                'unassigned' => 'Unassigned',
                'due-soon' => 'Due Soon',
                'overdue' => 'Overdue',
            ],
            'tickets' => $tickets,
            'counts' => $counts,
            'priorities' => $priorities,
            'statuses' => $statuses,
        ]);
    }

    /**
     * Calculate counts for each tab
     */
    private function calculateTabCounts($user)
    {
        $baseQuery = Ticket::query();
        
        // Apply role-based filtering
        if ($user->hasRole('agent')) {
            $baseQuery->where('assigned_to', $user->id);
        } elseif ($user->hasRole('customer')) {
            $baseQuery->where('created_by', $user->id);
        }
        
        $closedStatusIds = TicketStatus::whereIn('name', ['Resolved', 'Closed'])->pluck('id');
        
        return [
            'open' => (clone $baseQuery)->whereHas('status', fn($q) => $q->where('name', 'Open'))->count(),
            'assigned-to-me' => (clone $baseQuery)->where('assigned_to', $user->id)->count(),
            'assigned-to-others' => (clone $baseQuery)->whereNotNull('assigned_to')->where('assigned_to', '!=', $user->id)->count(),
            'unassigned' => (clone $baseQuery)->whereNull('assigned_to')->count(),
            'due-soon' => (clone $baseQuery)->whereBetween('due_date', [now(), now()->addHours(24)])
                ->whereNotIn('status_id', $closedStatusIds)->count(),
            'overdue' => (clone $baseQuery)->where('due_date', '<', now())
                ->whereNotIn('status_id', $closedStatusIds)->count(),
        ];
    }

    /**
     * Show create ticket form.
     */
    public function create()
    {
        $user = auth()->user();
        
        // Check permission
        if (!$user->hasPermission('tickets.create')) {
            abort(403, 'You do not have permission to create tickets.');
        }
        
        $categories = TicketCategory::where('status', 'active')->orderBy('sort_order')->get();
        $priorities = TicketPriority::where('status', 'active')->orderBy('sort_order')->get();
        
        return view('tickets.create', compact('categories', 'priorities'));
    }

    /**
     * Store a new ticket.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        // Check permission
        if (!$user->hasPermission('tickets.create')) {
            abort(403, 'You do not have permission to create tickets.');
        }
        
        // Get attachment settings
        $maxAttachmentSize = (int) setting('max_attachment_size', 10) * 1024; // Convert MB to KB
        $allowedFileTypes = setting('allowed_file_types', 'pdf,doc,docx,xls,xlsx,jpg,jpeg,png,gif,zip');
        
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority_id' => 'required|exists:ticket_priorities,id',
            'category_id' => 'nullable|exists:ticket_categories,id',
            'attachments.*' => 'nullable|file|max:' . $maxAttachmentSize . '|mimes:' . $allowedFileTypes,
        ]);

        // Check content for spam/profanity using API if enabled
        if (SpamCheckService::checkOnTicket()) {
            $contentToCheck = $validated['subject'] . ' ' . $validated['description'];
            $spamContentCheck = SpamCheckService::checkContent($contentToCheck);
            
            if (!$spamContentCheck['is_clean']) {
                $issues = collect($spamContentCheck['issues'])->pluck('reason')->implode('; ');
                return back()->withErrors(['description' => 'Your ticket was blocked: ' . $issues])->withInput();
            }
        }

        // Calculate SLA due date based on priority + category
        $slaRule = SlaRule::where('priority_id', $validated['priority_id'])
            ->where(function($q) use ($validated) {
                $q->where('category_id', $validated['category_id'] ?? null)
                  ->orWhereNull('category_id');
            })
            ->where('status', 'active')
            ->orderByRaw('category_id IS NOT NULL DESC')
            ->first();
        
        // Get default "Open" status
        $defaultStatus = TicketStatus::where('is_default', true)->first() 
            ?? TicketStatus::where('name', 'Open')->first();
        
        $ticket = Ticket::create([
            'created_by' => auth()->id(),
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'priority_id' => $validated['priority_id'],
            'category_id' => $validated['category_id'] ?? null,
            'status_id' => $defaultStatus->id,
            'sla_rule_id' => $slaRule->id ?? null,
            'due_date' => $slaRule ? now()->addHours($slaRule->response_time_hours) : null,
        ]);
        
        // Handle attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('ticket_attachments', 'public');
                TicketAttachment::create([
                    'ticket_id' => $ticket->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'uploaded_by' => auth()->id(),
                ]);
            }
        }
        
        // Send email notification to ticket creator
        NotificationService::sendTicketCreated($ticket);
        
        // Log activity
        ActivityLogService::logCreate($ticket, 'tickets');
        
        return redirect()->route('tickets.index')->with('success', 'Ticket created successfully.');
    }

    /**
     * Display ticket details.
     */
    public function show($id)
    {
        $ticket = Ticket::with([
            'creator', 'assignee', 'assignees', 'category', 'priority', 'status', 'slaRule',
            'replies.user', 'replies.attachments', 'attachments.uploader'
        ])->findOrFail($id);
        
        // Permission check
        $user = auth()->user();
        
        // Agent can view if assigned (either old single assignment OR new multiple assignments)
        if ($user->hasRole('agent')) {
            $isAssigned = $ticket->assigned_to === $user->id || 
                          $ticket->assignees->contains('id', $user->id);
            if (!$isAssigned) {
                abort(403, 'You are not assigned to this ticket.');
            }
        }
        
        if ($user->hasRole('customer') && $ticket->created_by !== $user->id) {
            abort(403, 'Unauthorized access.');
        }
        
        // Get users who can be assigned (Agents and Administrators only)
        $agents = User::with('role')
            ->whereHas('role', fn($q) => $q->whereIn('slug', ['administrator', 'agent']))
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
        
        $statuses = TicketStatus::where('status', 'active')->orderBy('sort_order')->get();
        $priorities = TicketPriority::where('status', 'active')->orderBy('sort_order')->get();
        
        return view('tickets.show', compact('ticket', 'agents', 'statuses', 'priorities'));
    }

    /**
     * Show edit form for ticket.
     */
    public function edit($id)
    {
        $ticket = Ticket::findOrFail($id);
        
        // Only administrator or creator can edit
        if (!$ticket->canBeEditedBy(auth()->user())) {
            abort(403, 'Unauthorized action.');
        }
        
        return response()->json($ticket);
    }

    /**
     * Update ticket subject and description.
     */
    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        
        // Only administrator or creator can edit
        if (!$ticket->canBeEditedBy(auth()->user())) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
        ]);
        
        $oldValues = $ticket->only(['subject', 'description']);
        $ticket->update($validated);
        
        // Log activity
        ActivityLogService::logUpdate($ticket, 'tickets', $oldValues, "Ticket updated: {$ticket->subject}");
        
        return redirect()->route('tickets.show', $ticket->id)
            ->with('success', 'Ticket updated successfully.');
    }

    /**
     * Add reply to ticket.
     */
    public function reply(Request $request, $id)
    {
        $user = auth()->user();
        
        // Check permission - reply requires edit permission
        if (!$user->hasPermission('tickets.edit')) {
            abort(403, 'You do not have permission to reply to tickets.');
        }
        
        $ticket = Ticket::findOrFail($id);
        
        // Get attachment settings
        $maxAttachmentSize = (int) setting('max_attachment_size', 10) * 1024; // Convert MB to KB
        $allowedFileTypes = setting('allowed_file_types', 'pdf,doc,docx,xls,xlsx,jpg,jpeg,png,gif,zip');
        
        $validated = $request->validate([
            'message' => 'required|string',
            'is_internal_note' => 'nullable|boolean',
            'working_time' => 'nullable|numeric|min:0',
            'attachments.*' => 'nullable|file|max:' . $maxAttachmentSize . '|mimes:' . $allowedFileTypes,
        ]);

        // Check for bad words and websites (local database)
        $contentCheck = ContentFilterService::checkContent($validated['message']);
        
        if ($contentCheck['detected']) {
            $warningMessage = 'Warning: Your message contains inappropriate content. ';
            
            if (!empty($contentCheck['bad_words'])) {
                $words = collect($contentCheck['bad_words'])->pluck('word')->implode(', ');
                $warningMessage .= "Bad words detected: {$words}. ";
            }
            
            if (!empty($contentCheck['bad_websites'])) {
                $websites = collect($contentCheck['bad_websites'])->pluck('url')->implode(', ');
                $warningMessage .= "Prohibited websites detected: {$websites}. ";
            }
            
            return redirect()->route('tickets.show', $ticket->id)
                ->with('error', $warningMessage);
        }

        // Additional spam API checks if enabled
        if (SpamCheckService::checkOnTicket()) {
            $spamContentCheck = SpamCheckService::checkContent($validated['message']);
            
            if (!$spamContentCheck['is_clean']) {
                $issues = collect($spamContentCheck['issues'])->pluck('reason')->implode('; ');
                return redirect()->route('tickets.show', $ticket->id)
                    ->with('error', 'Your message was blocked: ' . $issues);
            }
        }

        $reply = TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $validated['message'],
            'is_internal_note' => $request->has('is_internal_note'),
            'working_time' => $validated['working_time'] ?? null,
        ]);
        
        // Handle attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('ticket_attachments', 'public');
                TicketAttachment::create([
                    'ticket_id' => $ticket->id,
                    'ticket_reply_id' => $reply->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'uploaded_by' => auth()->id(),
                ]);
            }
        }
        
        // Send email notification
        NotificationService::sendTicketReply($ticket, $reply);
        
        // Log activity
        ActivityLogService::log('reply', 'tickets', "Reply added to Ticket #{$ticket->id}: {$ticket->subject}", $ticket);
        
        $successMessage = 'Reply added successfully.';
        if ($validated['working_time'] ?? null) {
            $successMessage .= ' Working time logged: ' . $validated['working_time'] . ' hours.';
        }
        
        return redirect()->route('tickets.show', $ticket->id)
            ->with('success', $successMessage);
    }

    /**
     * Assign ticket to agent.
     */
    public function assign(Request $request, $id)
    {
        $ticket = Ticket::with('assignees')->findOrFail($id);
        
        // Only users with assign permission can assign
        if (!$ticket->canUpdateStatusAndAssign(auth()->user())) {
            abort(403, 'You do not have permission to assign tickets.');
        }
        
        // Validate - allow empty array (unassign all)
        $validated = $request->validate([
            'assigned_to' => 'nullable|array',
            'assigned_to.*' => 'exists:users,id',
        ]);
        
        // Get assigned_to array (empty array if null/not provided)
        $assignedToIds = $validated['assigned_to'] ?? [];
        
        // Get current assignees
        $currentAssignees = $ticket->assignees->pluck('name')->toArray();
        $currentAssigneesText = empty($currentAssignees) ? 'Unassigned' : implode(', ', $currentAssignees);
        
        // Get new assignees (empty collection if unassigning all)
        $newAgents = empty($assignedToIds) ? collect() : User::whereIn('id', $assignedToIds)->get();
        $newAssigneesText = $newAgents->isEmpty() ? 'Unassigned' : $newAgents->pluck('name')->implode(', ');
        
        // Sync assignments (add new, remove old) - empty array will remove all
        $ticket->assignees()->sync($assignedToIds);
        
        // Also update the primary assigned_to field (for backward compatibility)
        $ticket->update(['assigned_to' => $assignedToIds[0] ?? null]);
        
        // Reload relationship
        $ticket->load('assignees', 'assignee');
        
        // Log assignment change
        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => "Ticket assignment changed from [{$currentAssigneesText}] to [{$newAssigneesText}]",
            'is_internal_note' => true,
            'assigned_from' => $currentAssigneesText,
            'assigned_to' => $newAssigneesText,
        ]);
        
        // Send email notification to newly assigned agents only
        $previousAssigneeIds = collect($currentAssignees)->pluck('id')->toArray();
        foreach ($newAgents as $agent) {
            // Only send if this is a new assignment
            if (!in_array($agent->id, $previousAssigneeIds)) {
                NotificationService::sendTicketAssigned($ticket, $agent);
            }
        }
        
        // Log activity
        ActivityLogService::logAssign($ticket, $newAgents->pluck('name')->toArray());
        
        // Success message
        $message = $newAgents->isEmpty() 
            ? 'Ticket unassigned successfully.' 
            : 'Ticket assigned to ' . $newAssigneesText . ' successfully.';
        
        return redirect()->route('tickets.show', $ticket->id)
            ->with('success', $message);
    }

    /**
     * Update ticket status.
     */
    public function updateStatus(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        
        // Only administrators can update status
        if (!$ticket->canUpdateStatusAndAssign(auth()->user())) {
            abort(403, 'Only administrators can update ticket status.');
        }
        
        $validated = $request->validate([
            'status_id' => 'required|exists:ticket_statuses,id',
        ]);
        
        $oldStatus = $ticket->status->name;
        $newStatus = TicketStatus::find($validated['status_id'])->name;
        
        $updateData = ['status_id' => $validated['status_id']];
        
        // Set resolved_at or closed_at
        if ($newStatus === 'Resolved' && !$ticket->resolved_at) {
            $updateData['resolved_at'] = now();
        } elseif ($newStatus === 'Closed' && !$ticket->closed_at) {
            $updateData['closed_at'] = now();
        }
        
        $ticket->update($updateData);
        
        // Log status change
        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => "Status changed from {$oldStatus} to {$newStatus}",
            'is_internal_note' => true,
            'status_changed_from' => $oldStatus,
            'status_changed_to' => $newStatus,
        ]);
        
        // Send email notification to ticket creator
        NotificationService::sendTicketStatusChanged($ticket, $oldStatus, $newStatus);
        
        // Log activity
        ActivityLogService::logStatusChange($ticket, 'tickets', $oldStatus, $newStatus);
        
        return redirect()->route('tickets.show', $ticket->id)
            ->with('success', 'Ticket status updated successfully.');
    }

    /**
     * Update ticket priority.
     */
    public function updatePriority(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        
        // Only administrators can update priority
        if (!$ticket->canUpdateStatusAndAssign(auth()->user())) {
            abort(403, 'Only administrators can update ticket priority.');
        }
        
        $validated = $request->validate([
            'priority_id' => 'required|exists:ticket_priorities,id',
        ]);
        
        $oldPriority = $ticket->priority->name;
        $newPriority = TicketPriority::find($validated['priority_id'])->name;
        
        $ticket->update(['priority_id' => $validated['priority_id']]);
        
        // Recalculate SLA due date
        $slaRule = SlaRule::where('priority_id', $validated['priority_id'])
            ->where(function($q) use ($ticket) {
                $q->where('category_id', $ticket->category_id)
                  ->orWhereNull('category_id');
            })
            ->where('status', 'active')
            ->orderByRaw('category_id IS NOT NULL DESC')
            ->first();
        
        if ($slaRule) {
            $ticket->update([
                'sla_rule_id' => $slaRule->id,
                'due_date' => now()->addHours($slaRule->response_time_hours),
            ]);
        }
        
        // Log priority change
        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => "Priority changed from {$oldPriority} to {$newPriority}",
            'is_internal_note' => true,
            'priority_changed_from' => $oldPriority,
            'priority_changed_to' => $newPriority,
        ]);
        
        // Log activity
        ActivityLogService::log('priority_change', 'tickets', "Ticket #{$ticket->id} priority changed from '{$oldPriority}' to '{$newPriority}'", $ticket);
        
        return redirect()->route('tickets.show', $ticket->id)
            ->with('success', 'Ticket priority updated successfully.');
    }

    /**
     * Soft delete ticket (move to recycle bin).
     */
    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        
        // Only administrator or creator can delete
        if (!$ticket->canBeDeletedBy(auth()->user())) {
            abort(403, 'Unauthorized action.');
        }
        
        // Log activity before delete
        ActivityLogService::logDelete($ticket, 'tickets');
        
        $ticket->delete(); // Soft delete

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket moved to recycle bin.');
    }

    /**
     * Restore ticket from recycle bin.
     */
    public function restore($id)
    {
        $ticket = Ticket::onlyTrashed()->findOrFail($id);
        
        // Only administrators can restore
        if (!auth()->user()->hasRole('administrator')) {
            abort(403, 'Only administrators can restore tickets.');
        }
        
        $ticket->restore();
        
        // Log activity
        ActivityLogService::logRestore($ticket, 'tickets');
        
        return redirect()->back()->with('success', 'Ticket restored successfully.');
    }

    /**
     * Permanently delete ticket.
     */
    public function forceDelete($id)
    {
        $ticket = Ticket::onlyTrashed()->findOrFail($id);
        
        // Only administrators can permanently delete
        if (!auth()->user()->hasRole('administrator')) {
            abort(403, 'Only administrators can permanently delete tickets.');
        }
        
        // Log activity before permanent delete
        ActivityLogService::log('force_delete', 'tickets', "Ticket permanently deleted: {$ticket->subject}", $ticket);
        
        $ticket->forceDelete();
        
        return redirect()->back()->with('success', 'Ticket permanently deleted.');
    }

    /**
     * Empty recycle bin.
     */
    public function emptyRecycleBin()
    {
        // Only administrators can empty recycle bin
        if (!auth()->user()->hasRole('administrator')) {
            abort(403, 'Only administrators can empty recycle bin.');
        }
        
        $count = Ticket::onlyTrashed()->count();
        Ticket::onlyTrashed()->forceDelete();
        
        // Log activity
        ActivityLogService::log('empty_recycle_bin', 'tickets', "Recycle bin emptied: {$count} tickets permanently deleted");
        
        return redirect()->back()->with('success', 'Recycle bin emptied successfully.');
    }
}
