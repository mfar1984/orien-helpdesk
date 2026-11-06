<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketPriority;
use App\Models\TicketStatus;
use App\Models\User;
use App\Services\ActivityLogService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ReportController extends Controller
{
    /**
     * Display reports dashboard.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!$user->hasPermission('reports.view')) {
            abort(403, 'You do not have permission to access Reports.');
        }

        $userRole = $user->role->slug ?? 'customer';
        $normalized = $this->normalizeFilters($request, $userRole);
        $viewData = $this->prepareViewData($user, $userRole, $normalized);

        $viewData['accounts'] = $userRole === 'administrator'
            ? User::query()->orderBy('name')->orderBy('id')->get()
            : collect();
        $viewData['statuses'] = TicketStatus::query()->orderBy('name')->orderBy('id')->get();
        $viewData['priorities'] = TicketPriority::query()->orderBy('name')->orderBy('id')->get();

        return view('reports.index', $viewData);
    }

    /**
     * Export report as PDF.
     */
    public function export(Request $request)
    {
        $user = auth()->user();

        if (!$user->hasPermission('reports.export')) {
            abort(403, 'You do not have permission to export Reports.');
        }

        $userRole = $user->role->slug ?? 'customer';
        $normalized = $this->normalizeFilters($request, $userRole);
        $viewData = $this->prepareViewData($user, $userRole, $normalized);

        $pdf = Pdf::loadView('reports.pdf', $viewData)->setPaper('a4', 'portrait');
        $bytes = $pdf->output();
        $filename = 'report_' . $normalized['startAt']->toDateString()
            . '_to_' . $normalized['endAt']->toDateString() . '.pdf';

        ActivityLogService::log(
            'export',
            'reports',
            'Report exported: ' . $normalized['startAt']->toDateString()
                . ' to ' . $normalized['endAt']->toDateString()
        );

        return response($bytes, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Length' => (string) strlen($bytes),
        ]);
    }

    /**
     * Validate and normalize all report filters in the application timezone.
     */
    private function normalizeFilters(Request $request, string $userRole): array
    {
        $rules = [
            'start_datetime' => ['nullable', 'string', 'size:16', 'date_format:Y-m-d\TH:i'],
            'end_datetime' => ['nullable', 'string', 'size:16', 'date_format:Y-m-d\TH:i'],
            'status_id' => [
                'nullable',
                'integer',
                'min:1',
                Rule::exists('ticket_statuses', 'id')->whereNull('deleted_at'),
            ],
            'priority_id' => [
                'nullable',
                'integer',
                'min:1',
                Rule::exists('ticket_priorities', 'id')->whereNull('deleted_at'),
            ],
        ];

        if ($userRole === 'administrator') {
            $rules['account_id'] = [
                'nullable',
                'integer',
                'min:1',
                Rule::exists('users', 'id')->whereNull('deleted_at'),
            ];
        }

        $validated = $request->validate($rules);
        $timezone = config('app.timezone');
        $now = Carbon::now($timezone);
        $startAt = !empty($validated['start_datetime'])
            ? Carbon::createFromFormat('Y-m-d\TH:i', $validated['start_datetime'], $timezone)
                ->setSecond(0)
                ->setMicrosecond(0)
            : $now->copy()->startOfMonth()->startOfDay();
        $endAt = !empty($validated['end_datetime'])
            ? Carbon::createFromFormat('Y-m-d\TH:i', $validated['end_datetime'], $timezone)
                ->endOfMinute()
            : $now->copy()->endOfMonth()->endOfDay();

        if ($endAt->lt($startAt)) {
            throw ValidationException::withMessages([
                'end_datetime' => 'The end date and time must be after or equal to the start date and time.',
            ]);
        }

        $accountId = $userRole === 'administrator' && !empty($validated['account_id'])
            ? (int) $validated['account_id']
            : null;
        $statusId = !empty($validated['status_id']) ? (int) $validated['status_id'] : null;
        $priorityId = !empty($validated['priority_id']) ? (int) $validated['priority_id'] : null;

        return [
            'startAt' => $startAt,
            'endAt' => $endAt,
            'startDatetime' => $startAt->format('Y-m-d\TH:i'),
            'endDatetime' => $endAt->format('Y-m-d\TH:i'),
            'accountId' => $accountId,
            'statusId' => $statusId,
            'priorityId' => $priorityId,
            'account' => $accountId ? User::find($accountId) : null,
            'status' => $statusId ? TicketStatus::find($statusId) : null,
            'priority' => $priorityId ? TicketPriority::find($priorityId) : null,
        ];
    }

    /**
     * Build the complete shared data contract for the web and PDF views.
     */
    private function prepareViewData($user, string $userRole, array $normalized): array
    {
        $generatedAt = Carbon::now(config('app.timezone'));
        $data = $this->getReportData($user, $userRole, $normalized);
        $generatorName = $this->fallbackLabel($user->name, 'Unknown user');
        $roleName = $this->fallbackLabel($user->role?->name, ucfirst($userRole));
        $scope = $this->scopeLabel($user, $userRole, $normalized, $generatorName);
        $accountLabel = $normalized['accountId']
            ? $this->fallbackLabel($normalized['account']?->name, 'Unknown account')
            : 'All accounts';

        return [
            'data' => $data,
            'user' => $user,
            'userRole' => $userRole,
            'normalized' => $normalized,
            'canExport' => $user->hasPermission('reports.export'),
            'metadata' => [
                'companyName' => $this->fallbackLabel(company_name(), 'ORIEN Helpdesk'),
                'generatedDateTime' => $generatedAt->format('M d, Y · H:i'),
                'generatedMonth' => $generatedAt->format('F Y'),
                'generatedYear' => $generatedAt->format('Y'),
                'generatorName' => $generatorName,
                'roleName' => $roleName,
                'scope' => $scope,
                'timezone' => config('app.timezone'),
            ],
            'filters' => [
                'period' => $normalized['startAt']->format('M d, Y H:i')
                    . ' – ' . $normalized['endAt']->format('M d, Y H:i')
                    . ' (' . config('app.timezone') . ')',
                'scope' => $scope,
                'status' => $normalized['statusId']
                    ? $this->fallbackLabel($normalized['status']?->name, 'Unknown status')
                    : 'All statuses',
                'priority' => $normalized['priorityId']
                    ? $this->fallbackLabel($normalized['priority']?->name, 'Unknown priority')
                    : 'All priorities',
                'account' => $accountLabel,
            ],
        ];
    }

    /**
     * Get report data from one role-aware, filtered ticket collection.
     */
    private function getReportData($user, string $userRole, array $normalized): array
    {
        $ticketsQuery = Ticket::query();
        $this->applyTicketScope($ticketsQuery, $user, $userRole, $normalized);

        $tickets = $ticketsQuery
            ->with([
                'category',
                'priority',
                'status',
            ])
            ->withMin('replies as first_reply_at', 'created_at')
            ->orderByDesc('tickets.created_at')
            ->orderByDesc('tickets.id')
            ->get();

        $totalTickets = $tickets->count();
        $openTickets = $tickets->filter(fn ($ticket) => $ticket->status?->slug === 'open')->count();
        $closedTickets = $tickets->filter(fn ($ticket) => $ticket->status?->slug === 'closed')->count();
        $inProgressTickets = $tickets->filter(fn ($ticket) => $ticket->status?->slug === 'in-progress')->count();
        $priorityDistribution = $this->prepareDistribution($tickets, 'priority', 'Unspecified priority');
        $categoryDistribution = $this->prepareDistribution($tickets, 'category', 'Uncategorized');
        $statusDistribution = $this->prepareDistribution($tickets, 'status', 'Unspecified status');
        [$avgResponseTime, $responseSampleCount] = $this->calculateAvgResponseTime($tickets);
        [$avgResolutionTime, $resolutionSampleCount] = $this->calculateAvgResolutionTime($tickets);
        $topAgents = in_array($userRole, ['administrator', 'agent'], true)
            ? $this->getTopAgents($user, $userRole, $normalized)
            : collect();
        $recentTickets = $tickets->take(10)->values();

        $topAgents->each(function ($agent) use ($topAgents) {
            $name = $this->fallbackLabel($agent->name, 'Unknown agent');
            $maxCount = (int) $topAgents->max('tickets_count');
            $agent->setAttribute('display_name', $name);
            $agent->setAttribute('initials', $this->initials($name));
            $agent->setAttribute('role_label', $this->fallbackLabel($agent->role?->name, 'Support Agent'));
            $agent->setAttribute('count_percentage', $maxCount > 0
                ? $this->percentage((int) $agent->tickets_count, $maxCount)
                : 0.0);
        });

        return [
            'totalTickets' => $totalTickets,
            'openTickets' => $openTickets,
            'closedTickets' => $closedTickets,
            'inProgressTickets' => $inProgressTickets,
            'ticketsByPriority' => collect($priorityDistribution)->pluck('count', 'label'),
            'ticketsByCategory' => collect($categoryDistribution)->pluck('count', 'label'),
            'ticketsByStatus' => collect($statusDistribution)->pluck('count', 'label'),
            'avgResponseTime' => $avgResponseTime,
            'avgResolutionTime' => $avgResolutionTime,
            'responseSampleCount' => $responseSampleCount,
            'resolutionSampleCount' => $resolutionSampleCount,
            'topAgents' => $topAgents,
            'satisfactionRate' => $this->calculateSatisfactionRate($tickets),
            'tickets' => $recentTickets,
            'percentages' => [
                'total' => $totalTickets > 0 ? 100.0 : 0.0,
                'closed' => $this->percentage($closedTickets, $totalTickets),
                'open' => $this->percentage($openTickets, $totalTickets),
                'inProgress' => $this->percentage($inProgressTickets, $totalTickets),
            ],
            'priorityDistribution' => $priorityDistribution,
            'categoryDistribution' => $categoryDistribution,
            'statusDistribution' => $statusDistribution,
            'observation' => $this->prepareObservation(
                $totalTickets,
                $statusDistribution,
                $categoryDistribution
            ),
            'recentTickets' => $recentTickets->map(function ($ticket) {
                return [
                    'id' => (int) $ticket->id,
                    'subject' => $this->fallbackLabel($ticket->subject, 'No subject'),
                    'category' => $this->fallbackLabel($ticket->category?->name, 'Uncategorized'),
                    'priority' => $this->fallbackLabel($ticket->priority?->name, 'Unspecified priority'),
                    'status' => $this->fallbackLabel($ticket->status?->name, 'Unspecified status'),
                    'createdDate' => $ticket->created_at
                        ->copy()
                        ->timezone(config('app.timezone'))
                        ->format('M d, Y'),
                ];
            }),
        ];
    }

    /**
     * Apply effective dates, IDs, and role isolation to a ticket query.
     */
    private function applyTicketScope($query, $user, string $userRole, array $normalized): void
    {
        $query->whereBetween('tickets.created_at', [
            $normalized['startAt'],
            $normalized['endAt'],
        ]);

        if ($userRole === 'customer') {
            $query->where('tickets.created_by', $user->id);
        } elseif ($userRole === 'agent') {
            $query->whereHas('assignees', function ($assigneeQuery) use ($user) {
                $assigneeQuery->where('users.id', $user->id);
            });
        } elseif ($userRole === 'administrator' && $normalized['accountId']) {
            $query->where('tickets.created_by', $normalized['accountId']);
        }

        if ($normalized['statusId']) {
            $query->where('tickets.status_id', $normalized['statusId']);
        }

        if ($normalized['priorityId']) {
            $query->where('tickets.priority_id', $normalized['priorityId']);
        }
    }

    /**
     * Prepare a stable, relationship-safe distribution.
     */
    private function prepareDistribution(Collection $tickets, string $relationship, string $fallback): array
    {
        $counts = [];

        foreach ($tickets as $ticket) {
            $label = $this->fallbackLabel($ticket->{$relationship}?->name, $fallback);
            $counts[$label] = ($counts[$label] ?? 0) + 1;
        }

        $total = $tickets->count();
        $items = [];
        foreach ($counts as $label => $count) {
            $items[] = [
                'label' => $label,
                'count' => (int) $count,
                'percentage' => $this->percentage((int) $count, $total),
            ];
        }

        usort($items, function ($left, $right) {
            return $right['count'] <=> $left['count'] ?: strcmp($left['label'], $right['label']);
        });

        return $items;
    }

    /**
     * Calculate average time to the first persisted reply.
     */
    private function calculateAvgResponseTime(Collection $tickets): array
    {
        $minutes = [];

        foreach ($tickets as $ticket) {
            if (empty($ticket->first_reply_at)) {
                continue;
            }

            $firstReplyAt = Carbon::parse($ticket->first_reply_at);
            if ($firstReplyAt->lt($ticket->created_at)) {
                continue;
            }

            $minutes[] = $ticket->created_at->diffInMinutes($firstReplyAt);
        }

        return [
            count($minutes) > 0 ? round(array_sum($minutes) / count($minutes) / 60, 1) : 0.0,
            count($minutes),
        ];
    }

    /**
     * Calculate average creation-to-last-update time for closed tickets.
     */
    private function calculateAvgResolutionTime(Collection $tickets): array
    {
        $minutes = $tickets
            ->filter(fn ($ticket) => $ticket->status?->slug === 'closed')
            ->filter(fn ($ticket) => $ticket->updated_at->gte($ticket->created_at))
            ->map(fn ($ticket) => $ticket->created_at->diffInMinutes($ticket->updated_at));

        if ($minutes->isEmpty()) {
            return [0.0, 0];
        }

        return [round($minutes->average() / 60, 1), $minutes->count()];
    }

    /**
     * Get eligible agents with counts from the same effective ticket scope.
     */
    private function getTopAgents($user, string $userRole, array $normalized): Collection
    {
        $query = User::query()
            ->whereHas('role', function ($roleQuery) {
                $roleQuery->where('slug', 'agent');
            })
            ->with('role')
            ->withCount(['assignedTickets as tickets_count' => function ($ticketQuery) use ($user, $userRole, $normalized) {
                $this->applyTicketScope($ticketQuery, $user, $userRole, $normalized);
            }]);

        if ($userRole === 'agent') {
            $query->where('users.id', $user->id);
        }

        return $query
            ->orderByDesc('tickets_count')
            ->orderBy('name')
            ->orderBy('users.id')
            ->limit(5)
            ->get();
    }

    private function prepareObservation(int $total, array $statuses, array $categories): array
    {
        if ($total === 0) {
            return [
                'headline' => 'No tickets match the selected period and filters.',
                'support' => 'Adjust the effective filters to review another part of the support workload.',
            ];
        }

        $status = $statuses[0] ?? null;
        $category = $categories[0] ?? null;
        $headline = $status
            ? number_format($status['count']) . ' ' . $this->ticketWord($status['count'])
                . ($status['count'] === 1 ? ' is ' : ' are ') . $status['label'] . ' (' . number_format($status['percentage'], 1) . '%).'
            : number_format($total) . ' ' . $this->ticketWord($total) . ' were recorded.';
        $support = $category
            ? $category['label'] . ' accounts for ' . number_format($category['count']) . ' '
                . $this->ticketWord($category['count']) . ' ('
                . number_format($category['percentage'], 1) . '%) in the selected period.'
            : 'No category distribution is available for the selected period.';

        return ['headline' => $headline, 'support' => $support];
    }

    private function scopeLabel($user, string $userRole, array $normalized, string $generatorName): string
    {
        if ($userRole === 'customer') {
            return 'Personal tickets only';
        }

        if ($userRole === 'agent') {
            return 'Tickets assigned to ' . $generatorName;
        }

        if ($userRole === 'administrator' && $normalized['accountId']) {
            return 'Tickets created by '
                . $this->fallbackLabel($normalized['account']?->name, 'Unknown account');
        }

        return 'All system tickets';
    }

    private function fallbackLabel($value, string $fallback): string
    {
        $label = trim((string) ($value ?? ''));

        return $label !== '' ? $label : $fallback;
    }

    private function initials(string $name): string
    {
        $parts = preg_split('/\s+/u', trim($name), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $initials = '';

        foreach (array_slice($parts, 0, 2) as $part) {
            $initials .= mb_strtoupper(mb_substr($part, 0, 1));
        }

        return $initials !== '' ? $initials : '?';
    }

    private function percentage(int $count, int $total): float
    {
        if ($total <= 0) {
            return 0.0;
        }

        return min(100.0, max(0.0, round($count / $total * 100, 1)));
    }

    private function ticketWord(int $count): string
    {
        return $count === 1 ? 'ticket' : 'tickets';
    }

    /**
     * Calculate satisfaction rate (placeholder).
     */
    private function calculateSatisfactionRate(Collection $tickets)
    {
        return 0;
    }
}
