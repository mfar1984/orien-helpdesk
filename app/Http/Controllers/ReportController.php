<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display reports dashboard.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Check permission
        if (!$user->hasPermission('reports.view')) {
            abort(403, 'You do not have permission to access Reports.');
        }
        
        $userRole = $user->role->slug ?? 'customer';
        
        // Handle datetime-local input (format: 2026-01-01T00:00)
        $startDatetime = $request->input('start_datetime');
        $endDatetime = $request->input('end_datetime');
        
        if ($startDatetime) {
            $startDateTime = Carbon::parse($startDatetime);
            $startDate = $startDateTime->toDateString();
            $startTime = $startDateTime->format('H:i');
        } else {
            $startDate = now()->startOfMonth()->toDateString();
            $startTime = '00:00';
        }
        
        if ($endDatetime) {
            $endDateTime = Carbon::parse($endDatetime);
            $endDate = $endDateTime->toDateString();
            $endTime = $endDateTime->format('H:i');
        } else {
            $endDate = now()->endOfMonth()->toDateString();
            $endTime = '23:59';
        }
        
        $accountId = $request->input('account_id');
        $statusId = $request->input('status_id');
        $priorityId = $request->input('priority_id');
        
        // Get filtered data based on role
        $data = $this->getReportData($user, $userRole, $startDate, $endDate, $startTime, $endTime, $accountId, $statusId, $priorityId);
        
        return view('reports.index', [
            'data' => $data,
            'userRole' => $userRole,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'startTime' => $startTime,
            'endTime' => $endTime,
        ]);
    }

    /**
     * Export report as PDF.
     */
    public function export(Request $request)
    {
        $user = auth()->user();
        
        // Check permission - requires export permission
        if (!$user->hasPermission('reports.export')) {
            abort(403, 'You do not have permission to export Reports.');
        }
        
        $userRole = $user->role->slug ?? 'customer';
        
        // Handle datetime-local input
        $startDatetime = $request->input('start_datetime');
        $endDatetime = $request->input('end_datetime');
        
        if ($startDatetime) {
            $startDateTime = Carbon::parse($startDatetime);
            $startDate = $startDateTime->toDateString();
            $startTime = $startDateTime->format('H:i');
        } else {
            $startDate = now()->startOfMonth()->toDateString();
            $startTime = '00:00';
        }
        
        if ($endDatetime) {
            $endDateTime = Carbon::parse($endDatetime);
            $endDate = $endDateTime->toDateString();
            $endTime = $endDateTime->format('H:i');
        } else {
            $endDate = now()->endOfMonth()->toDateString();
            $endTime = '23:59';
        }
        
        $accountId = $request->input('account_id');
        $statusId = $request->input('status_id');
        $priorityId = $request->input('priority_id');
        
        // Get filtered data
        $data = $this->getReportData($user, $userRole, $startDate, $endDate, $startTime, $endTime, $accountId, $statusId, $priorityId);
        
        // Generate PDF
        $pdf = Pdf::loadView('reports.pdf', [
            'data' => $data,
            'userRole' => $userRole,
            'user' => $user,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'companyName' => company_name(),
        ]);
        
        // Set paper size and orientation
        $pdf->setPaper('a4', 'portrait');
        
        // Generate filename
        $filename = 'report_' . $startDate . '_to_' . $endDate . '.pdf';
        
        // Log export activity
        ActivityLogService::log('export', 'reports', "Report exported: {$startDate} to {$endDate}");
        
        return $pdf->download($filename);
    }

    /**
     * Get report data based on user role and date range.
     */
    private function getReportData($user, $userRole, $startDate, $endDate, $startTime = '00:00', $endTime = '23:59', $accountId = null, $statusId = null, $priorityId = null)
    {
        // Base query with date & time filter
        $startDateTime = Carbon::parse($startDate . ' ' . $startTime);
        $endDateTime = Carbon::parse($endDate . ' ' . $endTime);
        
        $ticketsQuery = Ticket::whereBetween('created_at', [
            $startDateTime,
            $endDateTime,
        ]);

        // Apply role-based filtering (DATA ISOLATION)
        if ($userRole === 'customer') {
            // Customer: Only their own tickets
            $ticketsQuery->where('created_by', $user->id);
        } elseif ($userRole === 'agent') {
            // Agent: Only assigned tickets
            $ticketsQuery->whereHas('assignees', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        } elseif ($userRole === 'administrator' && $accountId) {
            // Administrator with account filter
            $ticketsQuery->where('created_by', $accountId);
        }
        // Administrator without filter: See all

        // Apply status filter
        if ($statusId) {
            $ticketsQuery->where('status_id', $statusId);
        }

        // Apply priority filter
        if ($priorityId) {
            $ticketsQuery->where('priority_id', $priorityId);
        }

        // Get tickets
        $tickets = $ticketsQuery->with(['creator', 'category', 'priority', 'status', 'assignees'])->get();

        // Calculate statistics
        $totalTickets = $tickets->count();
        $openTickets = $tickets->where('status.slug', 'open')->count();
        $closedTickets = $tickets->where('status.slug', 'closed')->count();
        $inProgressTickets = $tickets->where('status.slug', 'in-progress')->count();
        
        // Get tickets by priority
        $ticketsByPriority = $tickets->groupBy('priority.name')->map->count();
        
        // Get tickets by category
        $ticketsByCategory = $tickets->groupBy('category.name')->map->count();
        
        // Get tickets by status
        $ticketsByStatus = $tickets->groupBy('status.name')->map->count();
        
        // Calculate response times
        $avgResponseTime = $this->calculateAvgResponseTime($tickets);
        $avgResolutionTime = $this->calculateAvgResolutionTime($tickets);
        
        // Get top performing agents (Admin/Agent only)
        $topAgents = [];
        if (in_array($userRole, ['administrator', 'agent'])) {
            $topAgents = $this->getTopAgents($startDate, $endDate, $userRole, $user);
        }
        
        // Customer satisfaction (if applicable)
        $satisfactionRate = $this->calculateSatisfactionRate($tickets);

        // Get trend data (last 7 days)
        $trend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $count = Ticket::whereDate('created_at', $date);
            
            // Apply same role-based filtering for trend
            if ($userRole === 'customer') {
                $count->where('created_by', $user->id);
            } elseif ($userRole === 'agent') {
                $count->whereHas('assignees', function ($q) use ($user) {
                    $q->where('users.id', $user->id);
                });
            }
            
            $trend[] = $count->count();
        }

        return [
            'totalTickets' => $totalTickets,
            'openTickets' => $openTickets,
            'closedTickets' => $closedTickets,
            'inProgressTickets' => $inProgressTickets,
            'ticketsByPriority' => $ticketsByPriority,
            'ticketsByCategory' => $ticketsByCategory,
            'ticketsByStatus' => $ticketsByStatus,
            'avgResponseTime' => $avgResponseTime,
            'avgResolutionTime' => $avgResolutionTime,
            'topAgents' => $topAgents,
            'satisfactionRate' => $satisfactionRate,
            'trend' => $trend,
            'tickets' => $tickets->take(10), // Recent 10 tickets for display
        ];
    }

    /**
     * Calculate average response time.
     */
    private function calculateAvgResponseTime($tickets)
    {
        $totalMinutes = 0;
        $count = 0;

        foreach ($tickets as $ticket) {
            $firstReply = $ticket->replies()->where('user_id', '!=', $ticket->user_id)->first();
            if ($firstReply) {
                $diff = $ticket->created_at->diffInMinutes($firstReply->created_at);
                $totalMinutes += $diff;
                $count++;
            }
        }

        return $count > 0 ? round($totalMinutes / $count / 60, 1) : 0; // Convert to hours
    }

    /**
     * Calculate average resolution time.
     */
    private function calculateAvgResolutionTime($tickets)
    {
        $closedTickets = $tickets->filter(function ($ticket) {
            return $ticket->status && $ticket->status->slug === 'closed';
        });

        if ($closedTickets->isEmpty()) {
            return 0;
        }

        $totalHours = $closedTickets->sum(function ($ticket) {
            return $ticket->created_at->diffInHours($ticket->updated_at);
        });

        return round($totalHours / $closedTickets->count(), 1);
    }

    /**
     * Get top performing agents.
     */
    private function getTopAgents($startDate, $endDate, $userRole, $user)
    {
        $query = User::whereHas('role', function ($q) {
            $q->where('slug', 'agent');
        })->withCount(['assignedTickets as tickets_count' => function ($q) use ($startDate, $endDate) {
            $q->whereBetween('tickets.created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ]);
        }]);

        // If agent, only show themselves
        if ($userRole === 'agent') {
            $query->where('id', $user->id);
        }

        return $query->orderByDesc('tickets_count')
            ->take(5)
            ->get();
    }

    /**
     * Calculate satisfaction rate (placeholder).
     */
    private function calculateSatisfactionRate($tickets)
    {
        // This is a placeholder - implement based on your rating system
        return 0;
    }
}
