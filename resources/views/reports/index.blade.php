@extends('layouts.app')

@section('title', 'Reports & Analytics')

@section('breadcrumb')
    <x-breadcrumb :items="[
        ['label' => 'Reports', 'active' => true]
    ]" />
@endsection

@section('content')
<div class="bg-white border border-gray-200">
    <!-- Page Header -->
    <div class="px-6 py-4 flex items-center justify-between border-b border-gray-200">
            <div>
            <h2 class="text-base font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Reports & Analytics</h2>
            <p class="text-xs text-gray-500 mt-0.5">View comprehensive ticket statistics and performance metrics</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-blue-600" style="font-size: 28px;">analytics</span>
        </div>
    </div>

    <!-- Data Isolation Notice -->
    <div class="px-6 py-3 border-b border-gray-200">
        @if($userRole === 'customer')
        <div class="bg-blue-50 border-l-4 border-blue-500 px-4 py-2.5">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-600" style="font-size: 18px;">info</span>
                <p class="text-xs text-blue-700 font-medium" style="font-family: Poppins, sans-serif;">You are viewing reports for your own tickets only.</p>
            </div>
        </div>
        @elseif($userRole === 'agent')
        <div class="bg-purple-50 border-l-4 border-purple-500 px-4 py-2.5">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-purple-600" style="font-size: 18px;">badge</span>
                <p class="text-xs text-purple-700 font-medium" style="font-family: Poppins, sans-serif;">You are viewing reports for tickets assigned to you.</p>
            </div>
        </div>
        @else
        <div class="bg-green-50 border-l-4 border-green-500 px-4 py-2.5">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-green-600" style="font-size: 18px;">admin_panel_settings</span>
                <p class="text-xs text-green-700 font-medium" style="font-family: Poppins, sans-serif;">You are viewing all system reports (Administrator access).</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Filter Row -->
    <div class="px-6 py-3 border-b border-gray-200">
        <form action="{{ route('reports.index') }}" method="GET" class="flex items-center gap-2">
            <div class="flex-1 flex items-center gap-2">
                <input type="datetime-local" name="start_datetime" value="{{ $startDate }}T{{ $startTime ?? '00:00' }}"
                    class="flex-1 px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500"
                    style="font-family: Poppins, sans-serif; min-height: 32px; font-size: 11px;">
                <span class="text-xs text-gray-400 shrink-0">to</span>
                <input type="datetime-local" name="end_datetime" value="{{ $endDate }}T{{ $endTime ?? '23:59' }}"
                    class="flex-1 px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500"
                    style="font-family: Poppins, sans-serif; min-height: 32px; font-size: 11px;">
            </div>

            @if($userRole === 'administrator')
            <select name="account_id" class="flex-1 px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500"
                style="font-family: Poppins, sans-serif; min-height: 32px; font-size: 11px;">
                <option value="">All Accounts</option>
                @foreach(\App\Models\User::orderBy('name')->get() as $account)
                <option value="{{ $account->id }}" {{ request('account_id') == $account->id ? 'selected' : '' }}>
                    {{ $account->name }}
                </option>
                @endforeach
            </select>
            @endif

            <select name="status_id" class="flex-1 px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500"
                style="font-family: Poppins, sans-serif; min-height: 32px; font-size: 11px;">
                <option value="">All Status</option>
                @foreach(\App\Models\TicketStatus::orderBy('name')->get() as $status)
                <option value="{{ $status->id }}" {{ request('status_id') == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                @endforeach
            </select>

            <select name="priority_id" class="flex-1 px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500"
                style="font-family: Poppins, sans-serif; min-height: 32px; font-size: 11px;">
                <option value="">All Priority</option>
                @foreach(\App\Models\TicketPriority::orderBy('name')->get() as $priority)
                <option value="{{ $priority->id }}" {{ request('priority_id') == $priority->id ? 'selected' : '' }}>{{ $priority->name }}</option>
                @endforeach
            </select>

            <button type="submit" class="shrink-0 inline-flex items-center gap-2 px-3 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition" style="min-height: 32px;">
                <span class="material-symbols-outlined" style="font-size: 14px;">filter_alt</span>
                FILTER
            </button>
            @if(auth()->user()->hasPermission('reports.export'))
            <button type="button" onclick="document.getElementById('export-form').submit()" class="shrink-0 inline-flex items-center gap-2 px-3 text-white text-xs font-medium rounded transition" style="min-height: 32px; background-color: #dc2626;">
                <span class="material-symbols-outlined" style="font-size: 14px;">picture_as_pdf</span>
                EXPORT
            </button>
            @endif
            <a href="{{ route('reports.index') }}" class="shrink-0 inline-flex items-center gap-2 px-3 text-gray-700 text-xs font-medium border border-gray-300 rounded hover:bg-gray-50 transition" style="min-height: 32px;">
                <span class="material-symbols-outlined" style="font-size: 14px;">refresh</span>
                RESET
            </a>
        </form>
    </div>

    @if(auth()->user()->hasPermission('reports.export'))
    <!-- Hidden Export Form -->
    <form id="export-form" action="{{ route('reports.export') }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="start_datetime" value="{{ $startDate }}T{{ $startTime ?? '00:00' }}">
        <input type="hidden" name="end_datetime" value="{{ $endDate }}T{{ $endTime ?? '23:59' }}">
        <input type="hidden" name="account_id" value="{{ request('account_id') }}">
        <input type="hidden" name="status_id" value="{{ request('status_id') }}">
        <input type="hidden" name="priority_id" value="{{ request('priority_id') }}">
    </form>
    @endif

    <script>
    function setDateRange(range) {
        const today = new Date();
        let startDate, endDate;
        
        switch(range) {
            case 'today':
                startDate = endDate = today;
                break;
            case 'yesterday':
                startDate = endDate = new Date(today.setDate(today.getDate() - 1));
                break;
            case 'week':
                startDate = new Date(today.setDate(today.getDate() - today.getDay()));
                endDate = new Date();
                break;
            case 'month':
                startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                endDate = new Date();
                break;
            case 'lastmonth':
                startDate = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                endDate = new Date(today.getFullYear(), today.getMonth(), 0);
                break;
        }
        
        document.querySelector('input[name="start_date"]').value = startDate.toISOString().split('T')[0];
        document.querySelector('input[name="end_date"]').value = endDate.toISOString().split('T')[0];
        document.querySelector('input[name="start_time"]').value = '00:00';
        document.querySelector('input[name="end_time"]').value = '23:59';
    }
    </script>

    <!-- Report Content -->
    <div class="px-6 py-4">
        <!-- Summary Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded p-3">
        <div class="flex items-center justify-between">
            <div>
                        <p class="text-xs text-blue-600 font-medium" style="font-family: Poppins, sans-serif;">Total Tickets</p>
                        <h3 class="text-xl font-bold text-blue-900 mt-0.5" style="font-family: Poppins, sans-serif;">{{ $data['totalTickets'] }}</h3>
                    </div>
                    <span class="material-symbols-outlined text-blue-600" style="font-size: 32px;">confirmation_number</span>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded p-3">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-green-600 font-medium" style="font-family: Poppins, sans-serif;">Closed Tickets</p>
                        <h3 class="text-xl font-bold text-green-900 mt-0.5" style="font-family: Poppins, sans-serif;">{{ $data['closedTickets'] }}</h3>
                    </div>
                    <span class="material-symbols-outlined text-green-600" style="font-size: 32px;">check_circle</span>
                </div>
            </div>

            <div class="bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-200 rounded p-3">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-orange-600 font-medium" style="font-family: Poppins, sans-serif;">Open Tickets</p>
                        <h3 class="text-xl font-bold text-orange-900 mt-0.5" style="font-family: Poppins, sans-serif;">{{ $data['openTickets'] }}</h3>
                    </div>
                    <span class="material-symbols-outlined text-orange-600" style="font-size: 32px;">pending</span>
        </div>
    </div>

            <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded p-3">
        <div class="flex items-center justify-between">
            <div>
                        <p class="text-xs text-purple-600 font-medium" style="font-family: Poppins, sans-serif;">In Progress</p>
                        <h3 class="text-xl font-bold text-purple-900 mt-0.5" style="font-family: Poppins, sans-serif;">{{ $data['inProgressTickets'] }}</h3>
                    </div>
                    <span class="material-symbols-outlined text-purple-600" style="font-size: 32px;">hourglass_empty</span>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 mb-4">
            <!-- Tickets by Status - Donut Chart -->
            <div class="border border-gray-200 rounded p-4">
                <h4 class="text-xs font-semibold text-gray-900 mb-3" style="font-family: Poppins, sans-serif;">Tickets by Status</h4>
                <div class="flex items-center justify-center" style="height: 180px;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            <!-- Tickets by Priority - Bar Chart -->
            <div class="border border-gray-200 rounded p-4">
                <h4 class="text-xs font-semibold text-gray-900 mb-3" style="font-family: Poppins, sans-serif;">Tickets by Priority</h4>
                <div style="height: 180px;">
                    <canvas id="priorityChart"></canvas>
                </div>
            </div>

            <!-- Tickets by Category - Horizontal Bar -->
            <div class="border border-gray-200 rounded p-4">
                <h4 class="text-xs font-semibold text-gray-900 mb-3" style="font-family: Poppins, sans-serif;">Tickets by Category</h4>
                <div style="height: 180px;">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 mb-4">
            <div class="border border-gray-200 rounded p-4">
                <h4 class="text-xs font-semibold text-gray-700 mb-4" style="font-family: Poppins, sans-serif;">Performance Metrics</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-3xl font-bold text-blue-600" style="font-family: Poppins, sans-serif;">{{ $data['avgResponseTime'] }}h</div>
                        <div class="text-xs text-blue-700 mt-1" style="font-family: Poppins, sans-serif;">Avg Response Time</div>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-3xl font-bold text-green-600" style="font-family: Poppins, sans-serif;">{{ $data['avgResolutionTime'] }}h</div>
                        <div class="text-xs text-green-700 mt-1" style="font-family: Poppins, sans-serif;">Avg Resolution Time</div>
                    </div>
                </div>
            </div>

            <!-- Ticket Trend - Line Chart -->
            <div class="border border-gray-200 rounded p-4">
                <h4 class="text-xs font-semibold text-gray-900 mb-3" style="font-family: Poppins, sans-serif;">Ticket Trend (Last 7 Days)</h4>
                <div style="height: 120px;">
                    <canvas id="trendChart"></canvas>
            </div>
        </div>
    </div>

        <!-- Top Agents (Admin/Agent only) -->
        @if(in_array($userRole, ['administrator', 'agent']) && !$data['topAgents']->isEmpty())
        <div class="border border-gray-200 rounded p-3 mb-3">
            <h4 class="text-xs font-semibold text-gray-900 mb-3" style="font-family: Poppins, sans-serif;">Top Performing Agents</h4>
            <div class="space-y-2">
                @foreach($data['topAgents'] as $agent)
                <div class="flex items-center justify-between py-1.5 border-b border-gray-100 last:border-0">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 bg-purple-100 rounded-full flex items-center justify-center">
                            <span class="text-xs font-semibold text-purple-600" style="font-family: Poppins, sans-serif;">{{ substr($agent->name, 0, 1) }}</span>
                        </div>
                        <span class="text-xs text-gray-900" style="font-family: Poppins, sans-serif;">{{ $agent->name }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-32 bg-gray-200 rounded-full h-2">
                            <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $data['topAgents']->max('tickets_count') > 0 ? ($agent->tickets_count / $data['topAgents']->max('tickets_count') * 100) : 0 }}%"></div>
                        </div>
                        <span class="text-xs font-semibold text-gray-900" style="font-family: Poppins, sans-serif; min-width: 60px; text-align: right;">{{ $agent->tickets_count }} tickets</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Recent Tickets -->
        <div class="border border-gray-200 rounded p-3">
            <h4 class="text-xs font-semibold text-gray-900 mb-3" style="font-family: Poppins, sans-serif;">Recent Tickets</h4>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700" style="font-family: Poppins, sans-serif;">ID</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700" style="font-family: Poppins, sans-serif;">Subject</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700" style="font-family: Poppins, sans-serif;">Category</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700" style="font-family: Poppins, sans-serif;">Priority</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700" style="font-family: Poppins, sans-serif;">Status</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700" style="font-family: Poppins, sans-serif;">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($data['tickets'] as $ticket)
                        <tr>
                            <td class="px-3 py-2 text-xs text-gray-900" style="font-family: Poppins, sans-serif;">#{{ $ticket->id }}</td>
                            <td class="px-3 py-2 text-xs text-gray-900" style="font-family: Poppins, sans-serif;">{{ Str::limit($ticket->subject, 40) }}</td>
                            <td class="px-3 py-2 text-xs text-gray-700" style="font-family: Poppins, sans-serif;">{{ $ticket->category->name ?? '-' }}</td>
                            <td class="px-3 py-2">
                                <span class="px-2 py-0.5 text-xs font-medium rounded" style="font-family: Poppins, sans-serif; background-color: {{ $ticket->priority->color ?? '#e5e7eb' }}20; color: {{ $ticket->priority->color ?? '#6b7280' }};">
                                    {{ $ticket->priority->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-3 py-2">
                                <span class="px-2 py-0.5 text-xs font-medium rounded" style="font-family: Poppins, sans-serif; background-color: {{ $ticket->status->color ?? '#e5e7eb' }}20; color: {{ $ticket->status->color ?? '#6b7280' }};">
                                    {{ $ticket->status->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-3 py-2 text-xs text-gray-700" style="font-family: Poppins, sans-serif;">{{ $ticket->created_at->format('M d, Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-3 py-6 text-center text-xs text-gray-500" style="font-family: Poppins, sans-serif;">No tickets found for this period</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart.js default settings
    Chart.defaults.font.family = 'Poppins, sans-serif';
    Chart.defaults.font.size = 10;
    
    // Status Donut Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($data['ticketsByStatus']->keys()) !!},
            datasets: [{
                data: {!! json_encode($data['ticketsByStatus']->values()) !!},
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',   // Blue
                    'rgba(16, 185, 129, 0.8)',   // Green
                    'rgba(245, 158, 11, 0.8)',   // Orange
                    'rgba(139, 92, 246, 0.8)',   // Purple
                    'rgba(239, 68, 68, 0.8)'     // Red
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        padding: 8,
                        font: { size: 10 }
                    }
                }
            }
        }
    });

    // Priority Bar Chart
    const priorityCtx = document.getElementById('priorityChart').getContext('2d');
    new Chart(priorityCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($data['ticketsByPriority']->keys()) !!},
            datasets: [{
                label: 'Tickets',
                data: {!! json_encode($data['ticketsByPriority']->values()) !!},
                backgroundColor: [
                    'rgba(239, 68, 68, 0.8)',    // Red - Critical
                    'rgba(245, 158, 11, 0.8)',   // Orange - High
                    'rgba(59, 130, 246, 0.8)',   // Blue - Medium
                    'rgba(16, 185, 129, 0.8)'    // Green - Low
                ],
                borderRadius: 6,
                borderSkipped: false,
                barThickness: 32
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    },
                    ticks: {
                        stepSize: 1,
                        font: { size: 10 }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 9 } }
                }
            }
        }
    });

    // Category Horizontal Bar Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($data['ticketsByCategory']->keys()) !!},
            datasets: [{
                label: 'Tickets',
                data: {!! json_encode($data['ticketsByCategory']->values()) !!},
                backgroundColor: 'rgba(16, 185, 129, 0.8)',
                borderRadius: 6,
                borderSkipped: false,
                barThickness: 20
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    },
                    ticks: {
                        stepSize: 1,
                        font: { size: 10 }
                    }
                },
                y: {
                    grid: { display: false },
                    ticks: { font: { size: 10 } }
                }
            }
        }
    });

    // Trend Line Chart (Last 7 Days)
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    const gradient = trendCtx.createLinearGradient(0, 0, 0, 120);
    gradient.addColorStop(0, 'rgba(59, 130, 246, 0.3)');
    gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');
    
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: ['6d ago', '5d ago', '4d ago', '3d ago', '2d ago', 'Yesterday', 'Today'],
            datasets: [{
                label: 'Tickets',
                data: [
                    {{ $data['trend'][0] ?? 0 }},
                    {{ $data['trend'][1] ?? 0 }},
                    {{ $data['trend'][2] ?? 0 }},
                    {{ $data['trend'][3] ?? 0 }},
                    {{ $data['trend'][4] ?? 0 }},
                    {{ $data['trend'][5] ?? 0 }},
                    {{ $data['trend'][6] ?? 0 }}
                ],
                borderColor: 'rgba(59, 130, 246, 1)',
                backgroundColor: gradient,
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: 'rgba(59, 130, 246, 1)',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    },
                    ticks: {
                        stepSize: 1,
                        font: { size: 9 }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 9 } }
                }
            }
        }
    });
});
</script>
@endsection
