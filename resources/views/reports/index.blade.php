@extends('layouts.app')

@section('title', 'Reports')

@section('breadcrumb')
    <x-breadcrumb :items="[
        ['label' => 'Reports', 'active' => true]
    ]" />
@endsection

@section('content')
<div class="rpt">
    <!-- Report Controls -->
    <div class="bg-white border border-gray-200 rpt-controls">
        <!-- Page Header -->
        <div class="px-6 py-4 flex items-center justify-between rpt-page-header">
            <div>
                <h2 class="text-base font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Reports</h2>
                <p class="text-xs text-gray-500 mt-0.5" style="font-family: Poppins, sans-serif;">Ticket volume, response performance and current support workload</p>
            </div>
            <div class="flex items-center gap-2 rpt-header-actions">
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center gap-2 px-3 bg-white border border-gray-300 text-gray-700 text-xs font-medium rounded hover:bg-gray-50 transition"
                   style="min-height: 32px; font-family: Poppins, sans-serif;">
                    <span class="material-symbols-outlined" style="font-size: 14px;">dashboard</span>
                    DASHBOARD
                </a>
                <button type="button" onclick="window.print()"
                        class="inline-flex items-center gap-2 px-3 bg-white border border-gray-300 text-gray-700 text-xs font-medium rounded hover:bg-gray-50 transition"
                        style="min-height: 32px; font-family: Poppins, sans-serif;">
                    <span class="material-symbols-outlined" style="font-size: 14px;">print</span>
                    PRINT
                </button>
                @if($canExport)
                    <button type="submit" form="report-export-form"
                            class="inline-flex items-center gap-2 px-3 text-white text-xs font-medium rounded transition"
                            style="min-height: 32px; background-color: #dc2626; font-family: Poppins, sans-serif;">
                        <span class="material-symbols-outlined" style="font-size: 14px;">picture_as_pdf</span>
                        DOWNLOAD PDF
                    </button>
                @endif
            </div>
        </div>

        <!-- Filter Form -->
        <div class="border-t border-gray-200">
            <div class="px-6 py-3">
                <form action="{{ route('reports.index') }}" method="GET" class="rpt-filter-form flex items-end gap-2">
                    <div class="flex-1 min-w-0 rpt-field rpt-field--date">
                        <label for="start_datetime">Start Date &amp; Time</label>
                        <input id="start_datetime" type="datetime-local" name="start_datetime"
                               value="{{ old('start_datetime', $normalized['startDatetime']) }}"
                               class="w-full px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500"
                               style="font-family: Poppins, sans-serif; min-height: 32px; font-size: 11px;"
                               @error('start_datetime') aria-invalid="true" aria-describedby="start_datetime_error" @enderror>
                        @error('start_datetime')<span id="start_datetime_error" class="rpt-field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="flex-1 min-w-0 rpt-field rpt-field--date">
                        <label for="end_datetime">End Date &amp; Time</label>
                        <input id="end_datetime" type="datetime-local" name="end_datetime"
                               value="{{ old('end_datetime', $normalized['endDatetime']) }}"
                               class="w-full px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500"
                               style="font-family: Poppins, sans-serif; min-height: 32px; font-size: 11px;"
                               @error('end_datetime') aria-invalid="true" aria-describedby="end_datetime_error" @enderror>
                        @error('end_datetime')<span id="end_datetime_error" class="rpt-field-error">{{ $message }}</span>@enderror
                    </div>
                    @if($userRole === 'administrator')
                        <div class="flex-1 min-w-0 rpt-field">
                            <label for="account_id">Account</label>
                            <select id="account_id" name="account_id"
                                    class="w-full px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500"
                                    style="font-family: Poppins, sans-serif; min-height: 32px; font-size: 11px;">
                                <option value="">All accounts</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}" @selected((string) old('account_id', $normalized['accountId']) === (string) $account->id)>{{ $account->name ?: 'Unknown account' }}</option>
                                @endforeach
                            </select>
                            @error('account_id')<span class="rpt-field-error">{{ $message }}</span>@enderror
                        </div>
                    @endif
                    <div class="flex-1 min-w-0 rpt-field">
                        <label for="status_id">Status</label>
                        <select id="status_id" name="status_id"
                                class="w-full px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500"
                                style="font-family: Poppins, sans-serif; min-height: 32px; font-size: 11px;">
                            <option value="">All statuses</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}" @selected((string) old('status_id', $normalized['statusId']) === (string) $status->id)>{{ $status->name ?: 'Unknown status' }}</option>
                            @endforeach
                        </select>
                        @error('status_id')<span class="rpt-field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="flex-1 min-w-0 rpt-field">
                        <label for="priority_id">Priority</label>
                        <select id="priority_id" name="priority_id"
                                class="w-full px-3 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-500"
                                style="font-family: Poppins, sans-serif; min-height: 32px; font-size: 11px;">
                            <option value="">All priorities</option>
                            @foreach($priorities as $priority)
                                <option value="{{ $priority->id }}" @selected((string) old('priority_id', $normalized['priorityId']) === (string) $priority->id)>{{ $priority->name ?: 'Unknown priority' }}</option>
                            @endforeach
                        </select>
                        @error('priority_id')<span class="rpt-field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="filter-buttons flex items-center gap-2">
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-3 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition"
                                style="min-height: 32px; font-family: Poppins, sans-serif;">
                            <span class="material-symbols-outlined" style="font-size: 14px;">filter_alt</span>
                            FILTER
                        </button>
                        <a href="{{ route('reports.index') }}"
                           class="inline-flex items-center gap-2 px-3 text-white text-xs font-medium rounded transition"
                           style="min-height: 32px; background-color: #dc2626; font-family: Poppins, sans-serif;">
                            <span class="material-symbols-outlined" style="font-size: 14px;">refresh</span>
                            RESET
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="rpt-alert mt-4 px-4 py-3 bg-red-50 border border-red-200 rounded text-red-700" role="alert" aria-live="polite" style="font-family: Poppins, sans-serif;">
            <p class="text-xs font-semibold flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-size: 16px;">error</span>
                The report filters could not be applied.
            </p>
            <ul class="rpt-alert-list mt-1.5 text-xs">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($canExport)
        <form id="report-export-form" action="{{ route('reports.export') }}" method="POST" hidden>
            @csrf
            <input type="hidden" name="start_datetime" value="{{ $normalized['startDatetime'] }}">
            <input type="hidden" name="end_datetime" value="{{ $normalized['endDatetime'] }}">
            @if($userRole === 'administrator')
                <input type="hidden" name="account_id" value="{{ $normalized['accountId'] }}">
            @endif
            <input type="hidden" name="status_id" value="{{ $normalized['statusId'] }}">
            <input type="hidden" name="priority_id" value="{{ $normalized['priorityId'] }}">
        </form>
    @endif

    <!-- A4 Report Sheet -->
    <div class="report-canvas">
        <section class="report-sheet" aria-label="Ticket reports and analytics">
            <header class="report-header">
                <div class="report-brand-line">
                    <div class="report-brand"><span class="report-brand-mark" aria-hidden="true"></span>{{ $metadata['companyName'] }}</div>
                    <div class="report-edition">Service Intelligence · {{ $metadata['generatedMonth'] }}</div>
                </div>
                <div class="report-hero">
                    <div>
                        <p class="report-kicker">Helpdesk performance brief</p>
                        <h1 class="report-title">Ticket Reports<br>&amp; Analytics</h1>
                        <p class="report-deck">A concise editorial view of ticket volume, response performance and the current support workload.</p>
                    </div>
                    <div class="report-total">
                        <strong>{{ number_format($data['totalTickets']) }}</strong>
                        <span>{{ $data['totalTickets'] === 1 ? 'Ticket recorded' : 'Tickets recorded' }}</span>
                    </div>
                </div>
                <dl class="report-metadata">
                    <div><dt>Report Period</dt><dd>{{ $filters['period'] }}</dd></div>
                    <div><dt>Generated</dt><dd>{{ $metadata['generatedDateTime'] }}</dd></div>
                    <div><dt>Generated By</dt><dd>{{ $metadata['generatorName'] }} ({{ $metadata['roleName'] }})</dd></div>
                    <div><dt>Scope</dt><dd>{{ $metadata['scope'] }}</dd></div>
                </dl>
            </header>

            <div class="report-filter-summary" aria-label="Effective filters">
                <span><strong>Period:</strong> {{ $filters['period'] }}</span>
                <span><strong>Scope:</strong> {{ $filters['scope'] }}</span>
                <span><strong>Status:</strong> {{ $filters['status'] }}</span>
                <span><strong>Priority:</strong> {{ $filters['priority'] }}</span>
                @if($userRole === 'administrator')
                    <span><strong>Account:</strong> {{ $filters['account'] }}</span>
                @endif
            </div>

            <div class="report-content">
                <section class="report-kpis" aria-label="Key performance indicators">
                    <article class="report-kpi">
                        <div class="report-kpi-label">Total Tickets</div>
                        <div class="report-kpi-main"><span class="report-kpi-value">{{ number_format($data['totalTickets']) }}</span><span class="report-kpi-percent">{{ number_format($data['percentages']['total'], 1) }}%</span></div>
                    </article>
                    <article class="report-kpi report-kpi--closed">
                        <div class="report-kpi-label">Closed</div>
                        <div class="report-kpi-main"><span class="report-kpi-value">{{ number_format($data['closedTickets']) }}</span><span class="report-kpi-percent">{{ number_format($data['percentages']['closed'], 1) }}%</span></div>
                    </article>
                    <article class="report-kpi report-kpi--open">
                        <div class="report-kpi-label">Open</div>
                        <div class="report-kpi-main"><span class="report-kpi-value">{{ number_format($data['openTickets']) }}</span><span class="report-kpi-percent">{{ number_format($data['percentages']['open'], 1) }}%</span></div>
                    </article>
                    <article class="report-kpi report-kpi--progress">
                        <div class="report-kpi-label">In Progress</div>
                        <div class="report-kpi-main"><span class="report-kpi-value">{{ number_format($data['inProgressTickets']) }}</span><span class="report-kpi-percent">{{ number_format($data['percentages']['inProgress'], 1) }}%</span></div>
                    </article>
                </section>

                <section class="report-editorial-grid">
                    <div>
                        <h2 class="report-section-label">Performance</h2>
                        <div class="report-performance">
                            <div class="report-time">
                                <div class="report-time-number">{{ $data['responseSampleCount'] > 0 ? number_format($data['avgResponseTime'], 1) . 'h' : '—' }}</div>
                                <div class="report-time-label">Average Response Time</div>
                                <div class="report-time-note">{{ $data['responseSampleCount'] > 0 ? 'Time to first recorded reply · ' . number_format($data['responseSampleCount']) . ' ' . ($data['responseSampleCount'] === 1 ? 'sample' : 'samples') : 'No recorded reply data' }}</div>
                            </div>
                            <div class="report-time">
                                <div class="report-time-number">{{ $data['resolutionSampleCount'] > 0 ? number_format($data['avgResolutionTime'], 1) . 'h' : '—' }}</div>
                                <div class="report-time-label">Average Resolution Time</div>
                                <div class="report-time-note">{{ $data['resolutionSampleCount'] > 0 ? 'Creation to last update · ' . number_format($data['resolutionSampleCount']) . ' ' . ($data['resolutionSampleCount'] === 1 ? 'sample' : 'samples') : 'No closed ticket data' }}</div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h2 class="report-section-label">Key observation</h2>
                        <div class="report-insight">
                            <strong>{{ $data['observation']['headline'] }}</strong>
                            <p>{{ $data['observation']['support'] }}</p>
                        </div>
                    </div>
                </section>

                <section class="report-breakdowns">
                    <article class="report-breakdown">
                        <h3>Tickets by Priority</h3>
                        @forelse($data['priorityDistribution'] as $item)
                            <div class="report-bar-row">
                                <span class="report-bar-name">{{ $item['label'] }}</span>
                                <span class="report-track"><span class="report-fill" style="width: {{ $item['percentage'] }}%"></span></span>
                                <span class="report-bar-count">{{ number_format($item['count']) }} · {{ number_format($item['percentage'], 1) }}%</span>
                            </div>
                        @empty
                            <p class="report-empty">No priority data available.</p>
                        @endforelse
                    </article>
                    <article class="report-breakdown report-breakdown--category">
                        <h3>Tickets by Category</h3>
                        @forelse($data['categoryDistribution'] as $item)
                            <div class="report-bar-row">
                                <span class="report-bar-name">{{ $item['label'] }}</span>
                                <span class="report-track"><span class="report-fill" style="width: {{ $item['percentage'] }}%"></span></span>
                                <span class="report-bar-count">{{ number_format($item['count']) }} · {{ number_format($item['percentage'], 1) }}%</span>
                            </div>
                        @empty
                            <p class="report-empty">No category data available.</p>
                        @endforelse
                    </article>
                </section>

                <section class="report-people-tickets {{ $userRole === 'customer' ? 'report-people-tickets--tickets-only' : '' }}">
                    @if(in_array($userRole, ['administrator', 'agent'], true))
                        <article>
                            <h2 class="report-section-label">Applicable Agents</h2>
                            @if($data['topAgents']->isEmpty())
                                <p class="report-empty">No eligible agents are available.</p>
                            @else
                                <div class="report-agent-list">
                                    @foreach($data['topAgents'] as $agent)
                                        <div class="report-agent">
                                            <span class="report-avatar">{{ $agent->initials }}</span>
                                            <div>
                                                <div class="report-agent-name">{{ $agent->display_name }}</div>
                                                <div class="report-agent-role">{{ $agent->role_label }}</div>
                                            </div>
                                            <div class="report-handled">{{ number_format($agent->tickets_count) }}<small>{{ $agent->tickets_count === 1 ? 'ticket' : 'tickets' }}</small></div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </article>
                    @endif
                    <article class="report-ticket-box">
                        <h2 class="report-section-label">Recent Tickets</h2>
                        <div class="report-ticket-wrapper">
                            <table class="report-ticket-table">
                                <thead><tr><th>ID</th><th>Subject</th><th>Category</th><th>Priority</th><th>Status</th><th>Date</th></tr></thead>
                                <tbody>
                                    @forelse($data['recentTickets'] as $ticket)
                                        <tr>
                                            <td class="report-ticket-id">#{{ $ticket['id'] }}</td>
                                            <td class="report-ticket-subject">{{ $ticket['subject'] }}</td>
                                            <td>{{ $ticket['category'] }}</td>
                                            <td><span class="report-pill report-pill--priority">{{ $ticket['priority'] }}</span></td>
                                            <td><span class="report-pill report-pill--status">{{ $ticket['status'] }}</span></td>
                                            <td>{{ $ticket['createdDate'] }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="report-empty">No tickets match the selected period and filters.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </article>
                </section>
            </div>

            <footer class="report-footer">
                <span>Confidential · {{ $metadata['companyName'] }} Helpdesk Analytics</span>
                <span>Internal service report · Generated {{ $metadata['generatedDateTime'] }} {{ $metadata['timezone'] }}</span>
            </footer>
        </section>
    </div>
</div>

<!-- All report styles are scoped under the single .rpt wrapper so nothing can leak -->
<!-- into the sidebar, header, breadcrumb, footer or any other page. The only unscoped -->
<!-- rules live in the print media block, where hiding the shared app chrome is intended. -->
<style>
    .rpt {
        --ink: #15202b;
        --muted: #66727d;
        --paper: #fffefb;
        --line: #dbe1e2;
        --blue: #1e66d0;
        --blue-soft: #e8f0fc;
        --teal: #128477;
        --teal-soft: #e6f4f1;
        --coral: #d95c48;
        --coral-soft: #faeae7;
        --amber: #b56d14;
        --amber-soft: #fbf0dd;
        --violet: #6954a2;
        --violet-soft: #efebf8;
        --charcoal: #21303f;
    }

    .rpt, .rpt *, .rpt *::before, .rpt *::after { box-sizing: border-box; }

    /* ---------- Report controls (page header + filter bar) ---------- */
    .rpt-filter-form { flex-wrap: wrap; }
    .rpt-filter-form label {
        display: block;
        margin-bottom: 4px;
        color: #6b7280;
        font-family: Poppins, sans-serif;
        font-size: 10px;
        font-weight: 600;
        letter-spacing: .04em;
        text-transform: uppercase;
    }
    .rpt-field { flex: 1 1 150px; }
    .rpt-field--date { flex: 1 1 185px; }
    .rpt-field-error {
        display: block;
        margin-top: 4px;
        color: #b91c1c;
        font-family: Poppins, sans-serif;
        font-size: 10px;
        line-height: 1.3;
    }
    .rpt-alert-list { padding-left: 26px; list-style: disc; }
    .rpt-alert-list li { margin-top: 2px; }

    @media screen and (max-width: 768px) {
        .rpt-page-header { flex-direction: column; align-items: flex-start; gap: .75rem; }
        .rpt-header-actions { width: 100%; flex-wrap: wrap; }
        .rpt-field, .rpt-field--date { flex: 1 1 100%; }
        .rpt-filter-form .filter-buttons { width: 100%; }
        .rpt-filter-form .filter-buttons button,
        .rpt-filter-form .filter-buttons a { flex: 1 1 auto; justify-content: center; }
    }

    /* ---------- A4 sheet ---------- */
    .rpt .report-canvas { margin-top: 1rem; }
    .rpt .report-sheet {
        width: 210mm;
        max-width: 100%;
        min-height: 297mm;
        margin: 0 auto;
        overflow: visible;
        color: var(--ink);
        background: var(--paper);
        border: 1px solid #d2d9dc;
        box-shadow: 0 12px 34px rgba(25, 39, 47, .14);
        font-family: "Segoe UI", Arial, Helvetica, sans-serif;
        font-size: 9pt;
        line-height: 1.4;
    }
    .rpt .report-header { padding: 10mm 13mm 7mm; }
    .rpt .report-brand-line { display: flex; align-items: center; justify-content: space-between; gap: 8mm; margin-bottom: 7mm; }
    .rpt .report-brand { display: flex; min-width: 0; align-items: center; gap: 3mm; color: var(--charcoal); font-size: 8pt; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; overflow-wrap: anywhere; }
    .rpt .report-brand-mark { flex: 0 0 auto; width: 6mm; height: 6mm; background: linear-gradient(135deg, var(--blue) 0 50%, var(--teal) 50%); border-radius: 1.5mm; }
    .rpt .report-edition { color: var(--muted); font-size: 7.6pt; text-align: right; }
    .rpt .report-hero { display: grid; grid-template-columns: 1.25fr .75fr; gap: 8mm; align-items: end; padding-bottom: 8mm; border-bottom: 1.2mm solid var(--ink); }
    .rpt .report-kicker { margin: 0 0 2mm; color: var(--blue); font-size: 8pt; font-weight: 750; letter-spacing: .14em; text-transform: uppercase; }
    .rpt .report-title { margin: 0; max-width: 110mm; font-size: 24pt; font-weight: 700; line-height: 1.02; letter-spacing: -.045em; }
    .rpt .report-deck { margin: 3mm 0 0; max-width: 110mm; color: var(--muted); font-size: 8.5pt; }
    .rpt .report-total { min-width: 0; text-align: right; }
    .rpt .report-total strong { display: block; font-size: 38pt; font-weight: 800; line-height: .82; letter-spacing: -.06em; }
    .rpt .report-total span { display: block; margin-top: 2mm; color: var(--muted); font-size: 7.8pt; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
    .rpt .report-metadata { display: grid; grid-template-columns: 1.2fr .9fr 1.2fr .8fr; gap: 4mm; margin: 0; padding-top: 5mm; }
    .rpt .report-metadata div { min-width: 0; }
    .rpt .report-metadata dt { margin: 0 0 1mm; color: var(--muted); font-size: 7.2pt; font-weight: 700; letter-spacing: .07em; text-transform: uppercase; }
    .rpt .report-metadata dd { margin: .8mm 0 0; font-size: 7.8pt; font-weight: 650; overflow-wrap: anywhere; }
    .rpt .report-filter-summary { display: flex; flex-wrap: wrap; gap: 2mm 5mm; margin: 0 13mm 3mm; padding: 3mm 0; border-top: 1px solid var(--line); border-bottom: 1px solid var(--line); }
    .rpt .report-filter-summary span { min-width: 0; color: var(--muted); font-size: 7.2pt; overflow-wrap: anywhere; }
    .rpt .report-filter-summary strong { color: var(--ink); }
    .rpt .report-content { padding: 0 13mm 10mm; }
    .rpt .report-kpis { display: grid; grid-template-columns: repeat(4, 1fr); margin: 2mm 0 6mm; border-bottom: 1px solid var(--line); }
    .rpt .report-kpi { min-width: 0; padding: 5mm 4mm 5mm 0; break-inside: avoid; }
    .rpt .report-kpi + .report-kpi { padding-left: 4mm; border-left: 1px solid var(--line); }
    .rpt .report-kpi-label { color: var(--muted); font-size: 7.6pt; font-weight: 700; }
    .rpt .report-kpi-main { display: flex; align-items: baseline; justify-content: space-between; gap: 2mm; margin-top: 1.5mm; }
    .rpt .report-kpi-value { font-size: 23pt; font-weight: 780; line-height: 1; letter-spacing: -.04em; }
    .rpt .report-kpi-percent { padding: .7mm 1.7mm; color: var(--blue); background: var(--blue-soft); border-radius: 3mm; font-size: 7.2pt; font-weight: 700; }
    .rpt .report-kpi--closed .report-kpi-percent { color: var(--teal); background: var(--teal-soft); }
    .rpt .report-kpi--open .report-kpi-percent { color: var(--amber); background: var(--amber-soft); }
    .rpt .report-kpi--progress .report-kpi-percent { color: var(--violet); background: var(--violet-soft); }
    .rpt .report-editorial-grid { display: grid; grid-template-columns: 1.22fr .78fr; gap: 5mm; margin-bottom: 6mm; }
    .rpt .report-section-label { display: flex; align-items: center; gap: 2mm; margin: 0 0 2.5mm; color: var(--ink); font-size: 8pt; font-weight: 800; letter-spacing: .09em; text-transform: uppercase; break-after: avoid-page; }
    .rpt .report-section-label::before { content: ""; flex: 0 0 auto; width: 7mm; height: .8mm; background: var(--blue); }
    .rpt .report-performance { display: grid; grid-template-columns: 1fr 1fr; min-height: 40mm; color: #fff; background: var(--charcoal); break-inside: avoid; }
    .rpt .report-time { min-width: 0; padding: 5mm; }
    .rpt .report-time + .report-time { border-left: 1px solid #43505c; }
    .rpt .report-time-number { color: #79b0ff; font-size: 24pt; font-weight: 760; line-height: 1; letter-spacing: -.04em; }
    .rpt .report-time:last-child .report-time-number { color: #72d1bd; }
    .rpt .report-time-label { margin-top: 2mm; font-size: 8pt; font-weight: 700; }
    .rpt .report-time-note { margin-top: 1mm; color: #afbac4; font-size: 7.3pt; overflow-wrap: anywhere; }
    .rpt .report-insight { min-height: 40mm; padding: 5mm; background: var(--blue-soft); border-top: 1mm solid var(--blue); break-inside: avoid; }
    .rpt .report-insight strong { display: block; font-size: 13pt; line-height: 1.2; overflow-wrap: anywhere; }
    .rpt .report-insight p { margin: 2.5mm 0 0; color: #4e6379; font-size: 7.8pt; overflow-wrap: anywhere; }
    .rpt .report-breakdowns { display: grid; grid-template-columns: 1fr 1fr; gap: 4mm; margin-bottom: 6mm; }
    .rpt .report-breakdown { min-width: 0; padding: 4.5mm 4mm; border: 1px solid var(--line); background: #fff; }
    .rpt .report-breakdown h3 { margin: 0 0 3mm; font-size: 9pt; font-weight: 700; break-after: avoid-page; }
    .rpt .report-bar-row { display: grid; grid-template-columns: minmax(23mm, 1fr) 1.5fr 14mm; gap: 2.5mm; align-items: center; margin: 3.5mm 0; }
    .rpt .report-bar-name { min-width: 0; font-size: 7.8pt; font-weight: 650; overflow-wrap: anywhere; }
    .rpt .report-track { height: 2mm; overflow: hidden; background: #e8ecee; border-radius: 2mm; }
    .rpt .report-fill { display: block; height: 100%; background: var(--coral); border-radius: inherit; }
    .rpt .report-breakdown--category .report-fill { background: var(--teal); }
    .rpt .report-bar-count { font-size: 7.6pt; font-weight: 800; text-align: right; white-space: nowrap; }
    .rpt .report-empty { margin: 4mm 0; padding: 4mm; color: var(--muted); background: #f6f8f8; border: 1px dashed #cdd5d8; text-align: center; }
    .rpt .report-people-tickets { display: grid; grid-template-columns: .78fr 1.42fr; gap: 5mm; align-items: start; }
    .rpt .report-people-tickets--tickets-only { grid-template-columns: 1fr; }
    .rpt .report-agent-list { border-top: 1px solid var(--ink); }
    .rpt .report-agent { display: grid; grid-template-columns: 8mm minmax(0, 1fr) auto; gap: 2.5mm; align-items: center; padding: 3.3mm 0; border-bottom: 1px solid var(--line); break-inside: avoid; }
    .rpt .report-avatar { display: grid; place-items: center; width: 8mm; height: 8mm; color: #fff; background: var(--ink); border-radius: 2mm; font-size: 7pt; font-weight: 800; }
    .rpt .report-agent:nth-child(2) .report-avatar { background: var(--blue); }
    .rpt .report-agent:nth-child(3) .report-avatar { background: var(--teal); }
    .rpt .report-agent-name { font-size: 8pt; font-weight: 700; overflow-wrap: anywhere; }
    .rpt .report-agent-role { color: var(--muted); font-size: 7.2pt; overflow-wrap: anywhere; }
    .rpt .report-handled { font-size: 11pt; font-weight: 800; text-align: right; }
    .rpt .report-handled small { display: block; color: var(--muted); font-size: 6.7pt; font-weight: 600; }
    .rpt .report-ticket-box { min-width: 0; border: 1px solid var(--line); background: #fff; }
    .rpt .report-ticket-box .report-section-label { margin: 0; padding: 3mm 3.5mm; border-bottom: 1px solid var(--line); }
    .rpt .report-ticket-wrapper { overflow-x: auto; }
    .rpt .report-ticket-table { width: 100%; min-width: 690px; table-layout: fixed; border-collapse: collapse; }
    .rpt .report-ticket-table thead { display: table-header-group; }
    .rpt .report-ticket-table th { padding: 2.2mm 2mm; color: var(--muted); background: #f5f7f7; font-size: 7pt; font-weight: 750; letter-spacing: .04em; text-align: left; text-transform: uppercase; }
    .rpt .report-ticket-table td { padding: 3.3mm 2mm; border-top: 1px solid var(--line); font-size: 7.7pt; overflow-wrap: anywhere; word-break: break-word; vertical-align: top; }
    .rpt .report-ticket-table tr { break-inside: avoid; }
    .rpt .report-ticket-table th:nth-child(1) { width: 9%; }
    .rpt .report-ticket-table th:nth-child(2) { width: 31%; }
    .rpt .report-ticket-table th:nth-child(3) { width: 17%; }
    .rpt .report-ticket-table th:nth-child(4) { width: 14%; }
    .rpt .report-ticket-table th:nth-child(5) { width: 13%; }
    .rpt .report-ticket-table th:nth-child(6) { width: 16%; }
    .rpt .report-ticket-id { color: var(--blue); font-weight: 800; }
    .rpt .report-ticket-subject { font-weight: 650; }
    .rpt .report-pill { display: inline-block; padding: .7mm 1.5mm; border-radius: 3mm; font-size: 6.9pt; font-weight: 700; }
    .rpt .report-pill--priority { color: #a44334; background: var(--coral-soft); }
    .rpt .report-pill--status { color: #225aa6; background: var(--blue-soft); }
    .rpt .report-footer { display: flex; justify-content: space-between; gap: 8mm; margin: 9mm 13mm 6mm; padding-top: 2mm; color: var(--muted); border-top: 1px solid var(--line); font-size: 7.2pt; break-inside: avoid; }
    .rpt .report-footer span { min-width: 0; overflow-wrap: anywhere; }

    /* The sheet no longer fits the content column: go fluid. */
    @media screen and (max-width: 1120px) {
        .rpt .report-sheet { width: 100%; min-height: 0; }
        .rpt .report-header { padding: 8mm 7mm 5mm; }
        .rpt .report-filter-summary { margin-right: 7mm; margin-left: 7mm; }
        .rpt .report-content { padding: 0 7mm 8mm; }
        .rpt .report-metadata { grid-template-columns: 1fr 1fr; }
        .rpt .report-footer { margin-right: 7mm; margin-left: 7mm; }
    }

    @media screen and (max-width: 900px) {
        .rpt .report-hero, .rpt .report-editorial-grid, .rpt .report-people-tickets { grid-template-columns: 1fr; }
        .rpt .report-total { text-align: left; }
        .rpt .report-kpis, .rpt .report-breakdowns { grid-template-columns: 1fr 1fr; }
        .rpt .report-kpi:nth-child(3) { padding-left: 0; border-left: 0; }
        .rpt .report-kpi:nth-child(n+3) { border-top: 1px solid var(--line); }
    }

    @media screen and (max-width: 560px) {
        .rpt .report-brand-line { align-items: flex-start; }
        .rpt .report-edition { max-width: 36%; }
        .rpt .report-title { font-size: 20pt; }
        .rpt .report-metadata, .rpt .report-kpis, .rpt .report-breakdowns, .rpt .report-performance { grid-template-columns: 1fr; }
        .rpt .report-kpi + .report-kpi { padding-left: 0; border-top: 1px solid var(--line); border-left: 0; }
        .rpt .report-time + .report-time { border-top: 1px solid #43505c; border-left: 0; }
        .rpt .report-footer { display: block; }
        .rpt .report-footer span { display: block; margin-top: 1mm; }
    }

    @page { size: A4 portrait; margin: 10mm 12mm 12mm; }

    @media print {
        /* Hide the shared application chrome so only the report sheet prints. */
        .sidebar,
        .sidebar-overlay,
        .header,
        .topbar,
        .breadcrumb-bar,
        .footer,
        .rpt-controls,
        .rpt-alert { display: none !important; }

        .main-content,
        .main-content-expanded,
        .main-content-collapsed { margin-left: 0 !important; }

        .main-content > main { padding: 0 !important; }

        .rpt, .rpt * { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
        .rpt { background: #fff !important; }
        .rpt .report-canvas { margin-top: 0 !important; padding: 0 !important; }
        .rpt .report-sheet { width: auto !important; max-width: none !important; min-height: 0 !important; margin: 0 !important; border: 0 !important; box-shadow: none !important; }
        .rpt .report-header { padding: 0 0 7mm !important; }
        .rpt .report-filter-summary { margin-right: 0 !important; margin-left: 0 !important; }
        .rpt .report-content { padding-right: 0 !important; padding-bottom: 0 !important; padding-left: 0 !important; }
        .rpt .report-ticket-wrapper { overflow: visible !important; }
        .rpt .report-ticket-table { width: 100% !important; min-width: 0 !important; }
        .rpt .report-footer { margin-right: 0 !important; margin-bottom: 0 !important; margin-left: 0 !important; }
    }
</style>
@endsection
