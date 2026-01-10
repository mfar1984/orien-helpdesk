<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Audit Log Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            color: #1f2937;
            line-height: 1.4;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #3b82f6;
        }
        .header h1 {
            font-size: 20px;
            color: #1e40af;
            margin-bottom: 5px;
        }
        .header p {
            color: #6b7280;
            font-size: 9px;
        }
        .stats-row {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            background: #f3f4f6;
            border-radius: 5px;
            padding: 10px;
        }
        .stat-box {
            display: table-cell;
            text-align: center;
            padding: 5px;
        }
        .stat-box .label {
            font-size: 8px;
            color: #6b7280;
            text-transform: uppercase;
        }
        .stat-box .value {
            font-size: 14px;
            font-weight: bold;
            color: #1f2937;
        }
        .filter-info {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 20px;
            font-size: 9px;
        }
        .filter-info strong {
            color: #92400e;
        }
        .date-section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .date-header {
            background: linear-gradient(90deg, #3b82f6, #60a5fa);
            color: white;
            padding: 8px 12px;
            border-radius: 5px 5px 0 0;
            font-size: 11px;
            font-weight: bold;
        }
        .date-header.today {
            background: linear-gradient(90deg, #3b82f6, #60a5fa);
        }
        .date-header.yesterday {
            background: linear-gradient(90deg, #8b5cf6, #a78bfa);
        }
        .date-header.other {
            background: linear-gradient(90deg, #6b7280, #9ca3af);
        }
        .timeline {
            border: 1px solid #e5e7eb;
            border-top: none;
            border-radius: 0 0 5px 5px;
            padding: 10px;
        }
        .timeline-item {
            position: relative;
            padding-left: 20px;
            padding-bottom: 15px;
            border-left: 2px solid #e5e7eb;
            margin-left: 5px;
        }
        .timeline-item:last-child {
            padding-bottom: 0;
            border-left: 2px solid transparent;
        }
        .timeline-dot {
            position: absolute;
            left: -6px;
            top: 0;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #3b82f6;
        }
        .timeline-dot.view { background: #3b82f6; }
        .timeline-dot.create { background: #10b981; }
        .timeline-dot.update { background: #f59e0b; }
        .timeline-dot.delete { background: #ef4444; }
        .timeline-dot.export { background: #6366f1; }
        .timeline-dot.reply { background: #06b6d4; }
        .timeline-dot.assign { background: #8b5cf6; }
        .timeline-dot.login { background: #22c55e; }
        .timeline-dot.logout { background: #6b7280; }
        
        .log-card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 5px;
        }
        .log-header {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        .log-action {
            display: table-cell;
            vertical-align: middle;
        }
        .action-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            color: white;
        }
        .action-badge.view { background: #3b82f6; }
        .action-badge.create { background: #10b981; }
        .action-badge.update { background: #f59e0b; }
        .action-badge.delete { background: #ef4444; }
        .action-badge.force_delete { background: #dc2626; }
        .action-badge.restore { background: #14b8a6; }
        .action-badge.export { background: #6366f1; }
        .action-badge.reply { background: #06b6d4; }
        .action-badge.assign { background: #8b5cf6; }
        .action-badge.status_change { background: #f97316; }
        .action-badge.priority_change { background: #eab308; }
        .action-badge.lock { background: #dc2626; }
        .action-badge.unlock { background: #16a34a; }
        .action-badge.suspend { background: #dc2626; }
        .action-badge.unsuspend { background: #16a34a; }
        .action-badge.login { background: #22c55e; }
        .action-badge.logout { background: #6b7280; }
        .action-badge.enable { background: #7c3aed; }
        .action-badge.disable { background: #7c3aed; }
        .action-badge.test { background: #ec4899; }
        
        .log-time {
            display: table-cell;
            text-align: right;
            vertical-align: middle;
            font-size: 9px;
            color: #6b7280;
        }
        .log-details {
            display: table;
            width: 100%;
            font-size: 9px;
        }
        .log-detail-row {
            display: table-row;
        }
        .log-detail-label {
            display: table-cell;
            width: 70px;
            color: #9ca3af;
            padding: 2px 0;
        }
        .log-detail-value {
            display: table-cell;
            color: #374151;
            padding: 2px 0;
        }
        .user-type-badge {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 8px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .user-type-badge.administrator { background: #fee2e2; color: #991b1b; }
        .user-type-badge.agent { background: #dbeafe; color: #1e40af; }
        .user-type-badge.customer { background: #d1fae5; color: #065f46; }
        
        .url-text {
            word-break: break-all;
            font-family: monospace;
            font-size: 8px;
            color: #6b7280;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 8px;
            color: #9ca3af;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>📋 AUDIT LOG REPORT</h1>
        <p>Generated on {{ $exportDate }}</p>
    </div>

    <!-- Stats -->
    <div class="stats-row">
        <div class="stat-box">
            <div class="label">Total Records</div>
            <div class="value">{{ number_format($totalLogs) }}</div>
        </div>
        <div class="stat-box">
            <div class="label">Date Range</div>
            <div class="value">{{ count($groupedLogs) }} day(s)</div>
        </div>
    </div>

    <!-- Filter Info -->
    @if(!empty($filterInfo))
    <div class="filter-info">
        <strong>Applied Filters:</strong>
        @if(isset($filterInfo['user']))
            User: {{ $filterInfo['user'] }}
        @endif
        @if(isset($filterInfo['from']))
            | From: {{ $filterInfo['from'] }}
        @endif
        @if(isset($filterInfo['to']))
            | To: {{ $filterInfo['to'] }}
        @endif
    </div>
    @endif

    <!-- Timeline -->
    @forelse($groupedLogs as $date => $logs)
        @php
            $dateObj = \Carbon\Carbon::parse($date);
            $isToday = $dateObj->isToday();
            $isYesterday = $dateObj->isYesterday();
            $dateLabel = $isToday ? 'TODAY - ' . $dateObj->format('d M Y') : ($isYesterday ? 'YESTERDAY - ' . $dateObj->format('d M Y') : $dateObj->format('l, d M Y'));
            $headerClass = $isToday ? 'today' : ($isYesterday ? 'yesterday' : 'other');
        @endphp
        
        <div class="date-section">
            <div class="date-header {{ $headerClass }}">
                📅 {{ $dateLabel }} ({{ $logs->count() }} activities)
            </div>
            <div class="timeline">
                @foreach($logs as $log)
                <div class="timeline-item">
                    <div class="timeline-dot {{ $log->action }}"></div>
                    <div class="log-card">
                        <div class="log-header">
                            <div class="log-action">
                                <span class="action-badge {{ $log->action }}">{{ strtoupper(str_replace('_', ' ', $log->action)) }}</span>
                                <span style="margin-left: 5px; font-size: 9px; color: #6b7280;">{{ ucfirst($log->module) }}</span>
                                @if($log->subject_name)
                                    <span style="margin-left: 5px; font-size: 9px; color: #374151; font-weight: bold;">- {{ $log->subject_name }}</span>
                                @endif
                            </div>
                            <div class="log-time">
                                🕐 {{ $log->created_at->format('H:i:s') }}
                            </div>
                        </div>
                        <div class="log-details">
                            <div class="log-detail-row">
                                <div class="log-detail-label">User:</div>
                                <div class="log-detail-value">
                                    {{ $log->user?->name ?? 'System' }}
                                    @if($log->user_type)
                                        <span class="user-type-badge {{ $log->user_type }}">{{ ucfirst($log->user_type) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="log-detail-row">
                                <div class="log-detail-label">IP Address:</div>
                                <div class="log-detail-value">{{ $log->ip_address ?? '-' }}</div>
                            </div>
                            <div class="log-detail-row">
                                <div class="log-detail-label">Response:</div>
                                <div class="log-detail-value">{{ $log->response_code }} ({{ $log->response_time_ms ?? 0 }}ms)</div>
                            </div>
                            <div class="log-detail-row">
                                <div class="log-detail-label">URL:</div>
                                <div class="log-detail-value url-text">{{ $log->url }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    @empty
        <div style="text-align: center; padding: 40px; color: #9ca3af;">
            <p style="font-size: 14px;">No audit logs found for the selected filter.</p>
        </div>
    @endforelse

    <!-- Footer -->
    <div class="footer">
        <p>This report was automatically generated by the Helpdesk System.</p>
        <p>Audit logs are retained for 3 days and automatically deleted thereafter.</p>
    </div>
</body>
</html>
