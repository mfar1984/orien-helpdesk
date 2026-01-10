@extends('layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'active' => true]
    ]" />
@endsection

@section('content')
<div class="bg-white border border-gray-200">
    <!-- Page Header -->
    <div class="px-6 py-4 flex items-center justify-between border-b border-gray-200">
        <div>
            <h2 class="text-base font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Dashboard Overview</h2>
            <p class="text-xs text-gray-500 mt-0.5">Welcome back! Here's what's happening with your helpdesk</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-blue-600" style="font-size: 28px;">dashboard</span>
        </div>
    </div>

    <!-- Data Isolation Notice -->
    <div class="px-6 py-3 border-b border-gray-200">
        @if($userRole === 'customer')
        <div class="bg-blue-50 border-l-4 border-blue-500 px-4 py-2.5">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-600" style="font-size: 18px;">info</span>
                <p class="text-xs text-blue-700 font-medium" style="font-family: Poppins, sans-serif;">You are viewing your personal ticket statistics.</p>
            </div>
        </div>
        @elseif($userRole === 'agent')
        <div class="bg-purple-50 border-l-4 border-purple-500 px-4 py-2.5">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-purple-600" style="font-size: 18px;">badge</span>
                <p class="text-xs text-purple-700 font-medium" style="font-family: Poppins, sans-serif;">You are viewing statistics for tickets assigned to you.</p>
            </div>
        </div>
        @else
        <div class="bg-green-50 border-l-4 border-green-500 px-4 py-2.5">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-green-600" style="font-size: 18px;">admin_panel_settings</span>
                <p class="text-xs text-green-700 font-medium" style="font-family: Poppins, sans-serif;">You are viewing all system statistics (Administrator access).</p>
            </div>
        </div>
        @endif
    </div>

    <div class="p-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
            <!-- Total Tickets -->
            <div class="border border-gray-200 rounded p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500" style="font-family: Poppins, sans-serif;">
                            @if($userRole === 'customer') My Tickets @elseif($userRole === 'agent') Assigned to Me @else Total Tickets @endif
                        </p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1" style="font-family: Poppins, sans-serif;">{{ $stats['total_tickets'] }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-blue-600" style="font-size: 20px;">confirmation_number</span>
                    </div>
                </div>
            </div>

            <!-- Open Tickets -->
            <div class="border border-gray-200 rounded p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500" style="font-family: Poppins, sans-serif;">Open Tickets</p>
                        <h3 class="text-2xl font-bold text-orange-600 mt-1" style="font-family: Poppins, sans-serif;">{{ $stats['open_tickets'] }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-orange-600" style="font-size: 20px;">pending</span>
                    </div>
                </div>
            </div>

            <!-- Closed Tickets -->
            <div class="border border-gray-200 rounded p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500" style="font-family: Poppins, sans-serif;">Closed Tickets</p>
                        <h3 class="text-2xl font-bold text-green-600 mt-1" style="font-family: Poppins, sans-serif;">{{ $stats['closed_tickets'] }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-green-600" style="font-size: 20px;">check_circle</span>
                    </div>
                </div>
            </div>

            <!-- Pending Tickets -->
            <div class="border border-gray-200 rounded p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500" style="font-family: Poppins, sans-serif;">Pending Tickets</p>
                        <h3 class="text-2xl font-bold text-red-600 mt-1" style="font-family: Poppins, sans-serif;">{{ $stats['pending_tickets'] }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-red-600" style="font-size: 20px;">schedule</span>
                    </div>
                </div>
            </div>
        </div>

        @if($userRole === 'administrator')
        <!-- Admin Stats -->
        <div class="grid grid-cols-3 gap-3 mb-4">
            <div class="border border-gray-200 rounded p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500" style="font-family: Poppins, sans-serif;">Total Users</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1" style="font-family: Poppins, sans-serif;">{{ $stats['total_users'] ?? 0 }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-indigo-600" style="font-size: 20px;">group</span>
                    </div>
                </div>
            </div>
            <div class="border border-gray-200 rounded p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500" style="font-family: Poppins, sans-serif;">Customers</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1" style="font-family: Poppins, sans-serif;">{{ $stats['total_customers'] ?? 0 }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-cyan-100 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-cyan-600" style="font-size: 20px;">person</span>
                    </div>
                </div>
            </div>
            <div class="border border-gray-200 rounded p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500" style="font-family: Poppins, sans-serif;">Agents</p>
                        <h3 class="text-2xl font-bold text-gray-900 mt-1" style="font-family: Poppins, sans-serif;">{{ $stats['total_agents'] ?? 0 }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-purple-600" style="font-size: 20px;">support_agent</span>
                    </div>
                </div>
            </div>
        </div>
        @endif

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

            <!-- Ticket Trend - Line Chart -->
            <div class="border border-gray-200 rounded p-4">
                <h4 class="text-xs font-semibold text-gray-900 mb-3" style="font-family: Poppins, sans-serif;">Ticket Trend (Last 7 Days)</h4>
                <div style="height: 180px;">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Tickets -->
        <div class="border border-gray-200 rounded">
            <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                <h4 class="text-xs font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Recent Tickets</h4>
                <a href="{{ route('tickets.index') }}" class="text-xs text-blue-600 hover:text-blue-700" style="font-family: Poppins, sans-serif;">View all →</a>
            </div>
            
            @if($recentTickets->isEmpty())
            <div class="text-center py-8">
                <span class="material-symbols-outlined text-gray-300" style="font-size: 48px;">inbox</span>
                <p class="text-xs text-gray-500 mt-2" style="font-family: Poppins, sans-serif;">No tickets yet</p>
            </div>
            @else
            <div class="divide-y divide-gray-100">
                @foreach($recentTickets as $ticket)
                <a href="{{ route('tickets.show', $ticket) }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0" style="background-color: {{ $ticket->priority->color ?? '#6b7280' }}20;">
                        <span class="material-symbols-outlined" style="font-size: 14px; color: {{ $ticket->priority->color ?? '#6b7280' }};">{{ $ticket->priority->icon ?? 'flag' }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-900 truncate" style="font-family: Poppins, sans-serif;">{{ $ticket->subject }}</p>
                        <p class="text-xs text-gray-500" style="font-family: Poppins, sans-serif;">
                            #{{ $ticket->ticket_number }} • {{ $ticket->creator->name ?? 'Unknown' }}
                        </p>
                    </div>
                    <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded text-xs" style="background-color: {{ $ticket->status->color ?? '#6b7280' }}20; color: {{ $ticket->status->color ?? '#6b7280' }}; font-family: Poppins, sans-serif;">
                        {{ $ticket->status->name ?? 'Unknown' }}
                    </span>
                    <span class="text-xs text-gray-400 shrink-0" style="font-family: Poppins, sans-serif;">{{ $ticket->created_at->diffForHumans() }}</span>
                </a>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Chart.defaults.font.family = 'Poppins, sans-serif';
    Chart.defaults.font.size = 10;
    
    // Status Donut Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($ticketsByStatus->keys()) !!},
            datasets: [{
                data: {!! json_encode($ticketsByStatus->values()) !!},
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(139, 92, 246, 0.8)',
                    'rgba(239, 68, 68, 0.8)'
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
                    labels: { boxWidth: 12, padding: 8, font: { size: 10 } }
                }
            }
        }
    });

    // Priority Bar Chart
    const priorityCtx = document.getElementById('priorityChart').getContext('2d');
    new Chart(priorityCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($ticketsByPriority->keys()) !!},
            datasets: [{
                label: 'Tickets',
                data: {!! json_encode($ticketsByPriority->values()) !!},
                backgroundColor: [
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(16, 185, 129, 0.8)'
                ],
                borderRadius: 6,
                barThickness: 32
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { stepSize: 1 } },
                x: { grid: { display: false } }
            }
        }
    });

    // Trend Line Chart
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    const gradient = trendCtx.createLinearGradient(0, 0, 0, 180);
    gradient.addColorStop(0, 'rgba(59, 130, 246, 0.3)');
    gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');
    
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: ['6d ago', '5d ago', '4d ago', '3d ago', '2d ago', 'Yesterday', 'Today'],
            datasets: [{
                label: 'Tickets',
                data: {!! json_encode($trend) !!},
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
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { stepSize: 1 } },
                x: { grid: { display: false } }
            }
        }
    });
});
</script>
@endsection
