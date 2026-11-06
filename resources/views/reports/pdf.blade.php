<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Ticket Reports &amp; Analytics · {{ $metadata['companyName'] }}</title>
    <style>
        @page { size: A4 portrait; margin: 10mm 12mm 12mm; }
        * { box-sizing: border-box; }
        body { margin: 0; color: #15202b; background: #fffefb; font-family: "DejaVu Sans", sans-serif; font-size: 8pt; line-height: 1.35; }
        table { width: 100%; border-collapse: collapse; }
        td, th { word-wrap: break-word; overflow-wrap: break-word; }
        .brand-table { margin-bottom: 7mm; }
        .brand-table td { vertical-align: middle; }
        .brand-name { width: 65%; color: #21303f; font-size: 8pt; font-weight: bold; letter-spacing: .6pt; text-transform: uppercase; }
        .brand-mark { display: inline-block; width: 6mm; height: 6mm; margin-right: 3mm; vertical-align: middle; background: #1e66d0; border-right: 3mm solid #128477; border-radius: 1mm; }
        .edition { color: #66727d; font-size: 7.3pt; text-align: right; }
        .hero-table { border-bottom: 3px solid #15202b; }
        .hero-table td { padding: 0 0 7mm; vertical-align: bottom; }
        .hero-copy { width: 72%; }
        .kicker { margin: 0 0 2mm; color: #1e66d0; font-size: 7.5pt; font-weight: bold; letter-spacing: 1pt; text-transform: uppercase; }
        h1 { margin: 0; font-size: 23pt; line-height: 1; letter-spacing: -1pt; }
        .deck { margin: 3mm 0 0; color: #66727d; font-size: 8pt; }
        .hero-total { width: 28%; text-align: right; }
        .hero-total strong { display: block; font-size: 36pt; line-height: .85; }
        .hero-total span { display: block; margin-top: 2mm; color: #66727d; font-size: 7.2pt; font-weight: bold; letter-spacing: .5pt; text-transform: uppercase; }
        .metadata { table-layout: fixed; margin: 5mm 0 4mm; }
        .metadata td { padding-right: 3mm; vertical-align: top; }
        .meta-label { color: #66727d; font-size: 6.7pt; font-weight: bold; letter-spacing: .4pt; text-transform: uppercase; }
        .meta-value { margin-top: 1mm; font-size: 7.3pt; font-weight: bold; }
        .filter-summary { margin-bottom: 3mm; padding: 3mm 0; color: #66727d; border-top: 1px solid #dbe1e2; border-bottom: 1px solid #dbe1e2; font-size: 6.8pt; line-height: 1.6; }
        .filter-summary strong { color: #15202b; }
        .filter-item { display: inline; margin-right: 4mm; }
        .kpis { table-layout: fixed; margin: 2mm 0 6mm; border-bottom: 1px solid #dbe1e2; page-break-inside: avoid; }
        .kpis td { width: 25%; padding: 4mm 3mm; border-left: 1px solid #dbe1e2; vertical-align: top; }
        .kpis td:first-child { padding-left: 0; border-left: 0; }
        .kpi-label { color: #66727d; font-size: 7.2pt; font-weight: bold; }
        .kpi-value { margin-top: 1.5mm; font-size: 21pt; font-weight: bold; line-height: 1; }
        .kpi-percent { display: inline-block; margin-top: 1.5mm; padding: .6mm 1.5mm; color: #1e66d0; background: #e8f0fc; border-radius: 3mm; font-size: 6.8pt; font-weight: bold; }
        .closed .kpi-percent { color: #128477; background: #e6f4f1; }
        .open .kpi-percent { color: #b56d14; background: #fbf0dd; }
        .progress .kpi-percent { color: #6954a2; background: #efebf8; }
        .section-label { margin: 0 0 2.5mm; color: #15202b; font-size: 7.5pt; font-weight: bold; letter-spacing: .7pt; text-transform: uppercase; page-break-after: avoid; }
        .section-label span { display: inline-block; width: 7mm; height: 2px; margin-right: 2mm; background: #1e66d0; vertical-align: middle; }
        .editorial { table-layout: fixed; margin-bottom: 6mm; page-break-inside: avoid; }
        .editorial > tbody > tr > td { vertical-align: top; }
        .performance-cell { width: 61%; padding-right: 4mm; }
        .insight-cell { width: 39%; }
        .performance { table-layout: fixed; color: #fff; background: #21303f; }
        .performance td { width: 50%; height: 38mm; padding: 5mm; border-left: 1px solid #43505c; vertical-align: top; }
        .performance td:first-child { border-left: 0; }
        .time-number { color: #79b0ff; font-size: 22pt; font-weight: bold; line-height: 1; }
        .performance td:last-child .time-number { color: #72d1bd; }
        .time-label { margin-top: 2mm; font-size: 7.5pt; font-weight: bold; }
        .time-note { margin-top: 1mm; color: #afbac4; font-size: 6.8pt; }
        .insight { height: 38mm; padding: 5mm; background: #e8f0fc; border-top: 3px solid #1e66d0; }
        .insight strong { display: block; font-size: 12pt; line-height: 1.2; }
        .insight p { margin: 2.5mm 0 0; color: #4e6379; font-size: 7.3pt; }
        .breakdown { margin-bottom: 5mm; padding: 4mm; border: 1px solid #dbe1e2; background: #fff; }
        .breakdown h3 { margin: 0 0 2mm; font-size: 8.5pt; page-break-after: avoid; }
        .distribution { table-layout: fixed; margin: 0; }
        .distribution thead { display: table-header-group; }
        .distribution tr { page-break-inside: avoid; }
        .distribution th { padding: 2mm; color: #66727d; background: #f5f7f7; font-size: 6.7pt; font-weight: bold; letter-spacing: .3pt; text-align: left; text-transform: uppercase; }
        .distribution td { padding: 2.5mm 2mm; border-top: 1px solid #e6eaeb; vertical-align: middle; }
        .distribution .name { width: 35%; font-weight: bold; }
        .distribution .bar-cell { width: 50%; }
        .distribution .count { width: 15%; font-weight: bold; text-align: right; white-space: nowrap; }
        .track { width: 100%; height: 2mm; background: #e8ecee; }
        .fill { height: 2mm; background: #d95c48; }
        .category-fill { background: #128477; }
        .empty { padding: 4mm; color: #66727d; background: #f6f8f8; border: 1px dashed #cdd5d8; text-align: center; }
        .agents { table-layout: fixed; margin-bottom: 6mm; border-top: 1px solid #15202b; }
        .agents thead { display: table-header-group; }
        .agents tr { page-break-inside: avoid; }
        .agents th { padding: 2mm; color: #66727d; background: #f5f7f7; font-size: 6.7pt; text-align: left; text-transform: uppercase; }
        .agents td { padding: 2.5mm 2mm; border-bottom: 1px solid #dbe1e2; vertical-align: middle; }
        .agent-initials { width: 12%; color: #fff; background: #21303f; font-weight: bold; text-align: center; }
        .agent-name { width: 48%; font-weight: bold; }
        .agent-role { width: 25%; color: #66727d; }
        .agent-count { width: 15%; font-weight: bold; text-align: right; }
        .tickets-box { border: 1px solid #dbe1e2; background: #fff; }
        .tickets-box .section-label { margin: 0; padding: 3mm; border-bottom: 1px solid #dbe1e2; }
        .tickets { table-layout: fixed; }
        .tickets thead { display: table-header-group; }
        .tickets tr { page-break-inside: avoid; }
        .tickets th { padding: 2mm 1.5mm; color: #66727d; background: #f5f7f7; font-size: 6.4pt; font-weight: bold; letter-spacing: .2pt; text-align: left; text-transform: uppercase; }
        .tickets td { padding: 2.7mm 1.5mm; border-top: 1px solid #dbe1e2; font-size: 7pt; vertical-align: top; }
        .tickets th:nth-child(1) { width: 8%; }
        .tickets th:nth-child(2) { width: 31%; }
        .tickets th:nth-child(3) { width: 17%; }
        .tickets th:nth-child(4) { width: 14%; }
        .tickets th:nth-child(5) { width: 14%; }
        .tickets th:nth-child(6) { width: 16%; }
        .ticket-id { color: #1e66d0; font-weight: bold; }
        .ticket-subject { font-weight: bold; word-wrap: break-word; }
        .pill { display: inline-block; padding: .6mm 1.2mm; border-radius: 3mm; font-size: 6.4pt; font-weight: bold; }
        .priority-pill { color: #a44334; background: #faeae7; }
        .status-pill { color: #225aa6; background: #e8f0fc; }
        .footer { margin-top: 8mm; padding-top: 2.5mm; color: #66727d; border-top: 1px solid #dbe1e2; font-size: 6.8pt; page-break-inside: avoid; }
        .footer-table td:last-child { text-align: right; }
    </style>
</head>
<body>
    <table class="brand-table">
        <tr>
            <td class="brand-name"><span class="brand-mark"></span>{{ $metadata['companyName'] }}</td>
            <td class="edition">Service Intelligence · {{ $metadata['generatedMonth'] }}</td>
        </tr>
    </table>

    <table class="hero-table">
        <tr>
            <td class="hero-copy">
                <p class="kicker">Helpdesk performance brief</p>
                <h1>Ticket Reports<br>&amp; Analytics</h1>
                <p class="deck">A concise editorial view of ticket volume, response performance and the current support workload.</p>
            </td>
            <td class="hero-total">
                <strong>{{ number_format($data['totalTickets']) }}</strong>
                <span>{{ $data['totalTickets'] === 1 ? 'Ticket recorded' : 'Tickets recorded' }}</span>
            </td>
        </tr>
    </table>

    <table class="metadata">
        <tr>
            <td style="width: 29%;"><div class="meta-label">Report Period</div><div class="meta-value">{{ $filters['period'] }}</div></td>
            <td style="width: 21%;"><div class="meta-label">Generated</div><div class="meta-value">{{ $metadata['generatedDateTime'] }}</div></td>
            <td style="width: 30%;"><div class="meta-label">Generated By</div><div class="meta-value">{{ $metadata['generatorName'] }} ({{ $metadata['roleName'] }})</div></td>
            <td style="width: 20%;"><div class="meta-label">Scope</div><div class="meta-value">{{ $metadata['scope'] }}</div></td>
        </tr>
    </table>

    <div class="filter-summary">
        <span class="filter-item"><strong>Period:</strong> {{ $filters['period'] }}</span>
        <span class="filter-item"><strong>Scope:</strong> {{ $filters['scope'] }}</span>
        <span class="filter-item"><strong>Status:</strong> {{ $filters['status'] }}</span>
        <span class="filter-item"><strong>Priority:</strong> {{ $filters['priority'] }}</span>
        @if($userRole === 'administrator')
            <span class="filter-item"><strong>Account:</strong> {{ $filters['account'] }}</span>
        @endif
    </div>

    <table class="kpis">
        <tr>
            <td><div class="kpi-label">Total Tickets</div><div class="kpi-value">{{ number_format($data['totalTickets']) }}</div><span class="kpi-percent">{{ number_format($data['percentages']['total'], 1) }}%</span></td>
            <td class="closed"><div class="kpi-label">Closed</div><div class="kpi-value">{{ number_format($data['closedTickets']) }}</div><span class="kpi-percent">{{ number_format($data['percentages']['closed'], 1) }}%</span></td>
            <td class="open"><div class="kpi-label">Open</div><div class="kpi-value">{{ number_format($data['openTickets']) }}</div><span class="kpi-percent">{{ number_format($data['percentages']['open'], 1) }}%</span></td>
            <td class="progress"><div class="kpi-label">In Progress</div><div class="kpi-value">{{ number_format($data['inProgressTickets']) }}</div><span class="kpi-percent">{{ number_format($data['percentages']['inProgress'], 1) }}%</span></td>
        </tr>
    </table>

    <table class="editorial">
        <tr>
            <td class="performance-cell">
                <h2 class="section-label"><span></span>Performance</h2>
                <table class="performance">
                    <tr>
                        <td>
                            <div class="time-number">{{ $data['responseSampleCount'] > 0 ? number_format($data['avgResponseTime'], 1) . 'h' : '—' }}</div>
                            <div class="time-label">Average Response Time</div>
                            <div class="time-note">{{ $data['responseSampleCount'] > 0 ? 'Time to first recorded reply · ' . number_format($data['responseSampleCount']) . ' ' . ($data['responseSampleCount'] === 1 ? 'sample' : 'samples') : 'No recorded reply data' }}</div>
                        </td>
                        <td>
                            <div class="time-number">{{ $data['resolutionSampleCount'] > 0 ? number_format($data['avgResolutionTime'], 1) . 'h' : '—' }}</div>
                            <div class="time-label">Average Resolution Time</div>
                            <div class="time-note">{{ $data['resolutionSampleCount'] > 0 ? 'Creation to last update · ' . number_format($data['resolutionSampleCount']) . ' ' . ($data['resolutionSampleCount'] === 1 ? 'sample' : 'samples') : 'No closed ticket data' }}</div>
                        </td>
                    </tr>
                </table>
            </td>
            <td class="insight-cell">
                <h2 class="section-label"><span></span>Key observation</h2>
                <div class="insight"><strong>{{ $data['observation']['headline'] }}</strong><p>{{ $data['observation']['support'] }}</p></div>
            </td>
        </tr>
    </table>

    <section class="breakdown">
        <h3>Tickets by Priority</h3>
        @if(empty($data['priorityDistribution']))
            <div class="empty">No priority data available.</div>
        @else
            <table class="distribution">
                <thead><tr><th class="name">Priority</th><th class="bar-cell">Distribution</th><th class="count">Count</th></tr></thead>
                <tbody>
                    @foreach($data['priorityDistribution'] as $item)
                        <tr>
                            <td class="name">{{ $item['label'] }}</td>
                            <td class="bar-cell"><div class="track"><div class="fill" style="width: {{ $item['percentage'] }}%;"></div></div></td>
                            <td class="count">{{ number_format($item['count']) }} · {{ number_format($item['percentage'], 1) }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </section>

    <section class="breakdown">
        <h3>Tickets by Category</h3>
        @if(empty($data['categoryDistribution']))
            <div class="empty">No category data available.</div>
        @else
            <table class="distribution">
                <thead><tr><th class="name">Category</th><th class="bar-cell">Distribution</th><th class="count">Count</th></tr></thead>
                <tbody>
                    @foreach($data['categoryDistribution'] as $item)
                        <tr>
                            <td class="name">{{ $item['label'] }}</td>
                            <td class="bar-cell"><div class="track"><div class="fill category-fill" style="width: {{ $item['percentage'] }}%;"></div></div></td>
                            <td class="count">{{ number_format($item['count']) }} · {{ number_format($item['percentage'], 1) }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </section>

    @if(in_array($userRole, ['administrator', 'agent'], true))
        <h2 class="section-label"><span></span>Applicable Agents</h2>
        @if($data['topAgents']->isEmpty())
            <div class="empty" style="margin-bottom: 6mm;">No eligible agents are available.</div>
        @else
            <table class="agents">
                <thead><tr><th>Initials</th><th>Agent</th><th>Role</th><th style="text-align: right;">Tickets</th></tr></thead>
                <tbody>
                    @foreach($data['topAgents'] as $agent)
                        <tr>
                            <td class="agent-initials">{{ $agent->initials }}</td>
                            <td class="agent-name">{{ $agent->display_name }}</td>
                            <td class="agent-role">{{ $agent->role_label }}</td>
                            <td class="agent-count">{{ number_format($agent->tickets_count) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endif

    <section class="tickets-box">
        <h2 class="section-label"><span></span>Recent Tickets</h2>
        <table class="tickets">
            <thead><tr><th>ID</th><th>Subject</th><th>Category</th><th>Priority</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
                @forelse($data['recentTickets'] as $ticket)
                    <tr>
                        <td class="ticket-id">#{{ $ticket['id'] }}</td>
                        @php
                            $pdfSubject = preg_replace_callback('/\S{24,}/u', static function (array $match) {
                                return implode("\u{200B}", mb_str_split($match[0], 24));
                            }, $ticket['subject']);
                        @endphp
                        <td class="ticket-subject">{{ $pdfSubject }}</td>
                        <td>{{ $ticket['category'] }}</td>
                        <td><span class="pill priority-pill">{{ $ticket['priority'] }}</span></td>
                        <td><span class="pill status-pill">{{ $ticket['status'] }}</span></td>
                        <td>{{ $ticket['createdDate'] }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="empty">No tickets match the selected period and filters.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <footer class="footer">
        <table class="footer-table"><tr><td>Confidential · {{ $metadata['companyName'] }} Helpdesk Analytics</td><td>Internal service report · Generated {{ $metadata['generatedDateTime'] }} {{ $metadata['timezone'] }}</td></tr></table>
    </footer>
</body>
</html>
