<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show the dashboard with role-based data isolation.
     */
    public function index()
    {
        $user = auth()->user();
        
        // Check permission
        if (!$user->hasPermission('dashboard.view') && !$user->hasPermission('overview')) {
            abort(403, 'You do not have permission to access the Dashboard.');
        }
        $userRole = $user->role->slug ?? 'customer';
        
        // Build base query with role-based isolation
        $ticketsQuery = $this->getIsolatedTicketsQuery($user, $userRole);
        
        // Get all tickets for charts
        $tickets = (clone $ticketsQuery)->with(['status', 'priority', 'category'])->get();
        
        // Calculate statistics
        $stats = [
            'total_tickets' => $tickets->count(),
            'open_tickets' => $tickets->filter(fn($t) => in_array($t->status->slug ?? '', ['open', 'in-progress', 'waiting', 'reopened']))->count(),
            'closed_tickets' => $tickets->filter(fn($t) => ($t->status->slug ?? '') === 'closed')->count(),
            'pending_tickets' => $tickets->filter(fn($t) => ($t->status->slug ?? '') === 'pending')->count(),
        ];
        
        // Tickets by Status (for chart)
        $ticketsByStatus = $tickets->groupBy(fn($t) => $t->status->name ?? 'Unknown')->map->count();
        
        // Tickets by Priority (for chart)
        $ticketsByPriority = $tickets->groupBy(fn($t) => $t->priority->name ?? 'Unknown')->map->count();
        
        // Tickets by Category (for chart)
        $ticketsByCategory = $tickets->groupBy(fn($t) => $t->category->name ?? 'Uncategorized')->map->count();
        
        // Trend data (last 7 days)
        $trend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $count = (clone $ticketsQuery)->whereDate('created_at', $date)->count();
            $trend[] = $count;
        }
        
        // Recent tickets
        $recentTickets = (clone $ticketsQuery)
            ->with(['creator', 'status', 'priority', 'assignees'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Additional stats for Administrator only
        if ($userRole === 'administrator') {
            $stats['total_users'] = User::where('status', 'active')->count();
            $stats['total_customers'] = User::whereHas('role', fn($q) => $q->where('slug', 'customer'))->where('status', 'active')->count();
            $stats['total_agents'] = User::whereHas('role', fn($q) => $q->where('slug', 'agent'))->where('status', 'active')->count();
        }
        
        return view('dashboard.index', [
            'stats' => $stats,
            'recentTickets' => $recentTickets,
            'userRole' => $userRole,
            'ticketsByStatus' => $ticketsByStatus,
            'ticketsByPriority' => $ticketsByPriority,
            'ticketsByCategory' => $ticketsByCategory,
            'trend' => $trend,
        ]);
    }
    
    /**
     * Get tickets query with role-based isolation.
     */
    private function getIsolatedTicketsQuery($user, $userRole)
    {
        $query = Ticket::query();
        
        if ($userRole === 'customer') {
            $query->where('created_by', $user->id);
        } elseif ($userRole === 'agent') {
            $query->whereHas('assignees', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        }
        
        return $query;
    }
}
