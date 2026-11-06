<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ticket Reports &amp; Analytics · {{ $metadata['companyName'] }}</title>
    <style>
        :root {
            --ink: #15202b;
            --muted: #66727d;
            --paper: #fffefb;
            --canvas: #e8ecee;
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
        * { box-sizing: border-box; }
        html, body { margin: 0; background: var(--canvas); }
        body { color: var(--ink); font-family: "Segoe UI", Arial, Helvetica, sans-serif; font-size: 9pt; line-height: 1.4; }
        button, input, select { font: inherit; }
        .report-toolbar { position: relative; z-index: 2; width: min(1180px, calc(100% - 32px)); margin: 18px auto 0; padding: 14px; background: #fff; border: 1px solid #d4dade; box-shadow: 0 5px 16px rgba(25, 39, 47, .08); }
        .report-toolbar__actions, .report-filter-form { display: flex; flex-wrap: wrap; align-items: end; gap: 10px; }
        .report-toolbar__actions { justify-content: space-between; margin-bottom: 12px; }
        .report-toolbar__title { margin: 0; font-size: 13pt; }
        .report-toolbar__buttons { display: flex; flex-wrap: wrap; gap: 7px; }
        .report-button { display: inline-flex; min-height: 34px; align-items: center; justify-content: center; padding: 7px 12px; color: var(--ink); background: #fff; border: 1px solid #bcc5ca; border-radius: 4px; cursor: pointer; font-weight: 700; text-decoration: none; }
        .report-button:hover { background: #f3f6f7; }
        .report-button--primary { color: #fff; background: var(--blue); border-color: var(--blue); }
        .report-button--primary:hover { background: #1755ae; }
        .report-button--pdf { color: #fff; background: var(--coral); border-color: var(--coral); }
        .report-button--pdf:hover { background: #bd4938; }
        .report-field { flex: 1 1 150px; min-width: 0; }
        .report-field--date { flex-basis: 190px; }
        .report-field label { display: block; margin-bottom: 4px; color: #4c5963; font-size: 7.5pt; font-weight: 800; letter-spacing: .04em; text-transform: uppercase; }
        .report-field input, .report-field select { width: 100%; min-height: 34px; padding: 6px 8px; color: var(--ink); background: #fff; border: 1px solid #bcc5ca; border-radius: 3px; }
        .report-field__error { display: block; margin-top: 4px; color: #9d3326; font-size: 7.2pt; line-height: 1.25; }
        .report-errors { width: min(1180px, calc(100% - 32px)); margin: 10px auto 0; padding: 11px 14px; color: #832e23; background: #fff2ef; border: 1px solid #e8a99f; }
        .report-errors strong { display: block; margin-bottom: 3px; }
        .report-errors ul { margin: 0; padding-left: 18px; }
        .report-canvas { padding: 18px 12px 32px; }
        .report-sheet { width: 210mm; min-height: 297mm; margin: 0 auto; overflow: visible; background: var(--paper); border: 1px solid #d2d9dc; box-shadow: 0 12px 34px rgba(25, 39, 47, .14); }
        .report-header { padding: 10mm 13mm 7mm; }
        .report-brand-line { display: flex; align-items: center; justify-content: space-between; gap: 8mm; margin-bottom: 7mm; }
        .report-brand { display: flex; min-width: 0; align-items: center; gap: 3mm; color: var(--charcoal); font-size: 8pt; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; overflow-wrap: anywhere; }
        .report-brand-mark { flex: 0 0 auto; width: 6mm; height: 6mm; background: linear-gradient(135deg, var(--blue) 0 50%, var(--teal) 50%); border-radius: 1.5mm; }
        .report-edition { color: var(--muted); font-size: 7.6pt; text-align: right; }
        .report-hero { display: grid; grid-template-columns: 1.25fr .75fr; gap: 8mm; align-items: end; padding-bottom: 8mm; border-bottom: 1.2mm solid var(--ink); }
        .report-kicker { margin: 0 0 2mm; color: var(--blue); font-size: 8pt; font-weight: 750; letter-spacing: .14em; text-transform: uppercase; }
        .report-title { margin: 0; max-width: 110mm; font-size: 24pt; line-height: 1.02; letter-spacing: -.045em; }
        .report-deck { margin: 3mm 0 0; max-width: 110mm; color: var(--muted); font-size: 8.5pt; }
        .report-total { min-width: 0; text-align: right; }
        .report-total strong { display: block; font-size: 38pt; line-height: .82; letter-spacing: -.06em; }
        .report-total span { display: block; margin-top: 2mm; color: var(--muted); font-size: 7.8pt; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
        .report-metadata { display: grid; grid-template-columns: 1.2fr .9fr 1.2fr .8fr; gap: 4mm; margin: 0; padding-top: 5mm; }
        .report-metadata div { min-width: 0; }
        .report-metadata dt { margin: 0 0 1mm; color: var(--muted); font-size: 7.2pt; font-weight: 700; letter-spacing: .07em; text-transform: uppercase; }
        .report-metadata dd { margin: .8mm 0 0; font-size: 7.8pt; font-weight: 650; overflow-wrap: anywhere; }
        .report-filter-summary { display: flex; flex-wrap: wrap; gap: 2mm 5mm; margin: 0 13mm 3mm; padding: 3mm 0; border-top: 1px solid var(--line); border-bottom: 1px solid var(--line); }
        .report-filter-summary span { min-width: 0; color: var(--muted); font-size: 7.2pt; overflow-wrap: anywhere; }
        .report-filter-summary strong { color: var(--ink); }
        .report-content { padding: 0 13mm 10mm; }
        .report-kpis { display: grid; grid-template-columns: repeat(4, 1fr); margin: 2mm 0 6mm; border-bottom: 1px solid var(--line); }
        .report-kpi { min-width: 0; padding: 5mm 4mm 5mm 0; break-inside: avoid; }
        .report-kpi + .report-kpi { padding-left: 4mm; border-left: 1px solid var(--line); }
        .report-kpi-label { color: var(--muted); font-size: 7.6pt; font-weight: 700; }
        .report-kpi-main { display: flex; align-items: baseline; justify-content: space-between; gap: 2mm; margin-top: 1.5mm; }
        .report-kpi-value { font-size: 23pt; font-weight: 780; line-height: 1; letter-spacing: -.04em; }
        .report-kpi-percent { padding: .7mm 1.7mm; color: var(--blue); background: var(--blue-soft); border-radius: 3mm; font-size: 7.2pt; font-weight: 700; }
        .report-kpi--closed .report-kpi-percent { color: var(--teal); background: var(--teal-soft); }
        .report-kpi--open .report-kpi-percent { color: var(--amber); background: var(--amber-soft); }
        .report-kpi--progress .report-kpi-percent { color: var(--violet); background: var(--violet-soft); }
        .report-editorial-grid { display: grid; grid-template-columns: 1.22fr .78fr; gap: 5mm; margin-bottom: 6mm; }
        .report-section-label { display: flex; align-items: center; gap: 2mm; margin: 0 0 2.5mm; color: var(--ink); font-size: 8pt; font-weight: 800; letter-spacing: .09em; text-transform: uppercase; break-after: avoid-page; }
        .report-section-label::before { content: ""; flex: 0 0 auto; width: 7mm; height: .8mm; background: var(--blue); }
        .report-performance { display: grid; grid-template-columns: 1fr 1fr; min-height: 40mm; color: #fff; background: var(--charcoal); break-inside: avoid; }
        .report-time { min-width: 0; padding: 5mm; }
        .report-time + .report-time { border-left: 1px solid #43505c; }
        .report-time-number { color: #79b0ff; font-size: 24pt; font-weight: 760; line-height: 1; letter-spacing: -.04em; }
        .report-time:last-child .report-time-number { color: #72d1bd; }
        .report-time-label { margin-top: 2mm; font-size: 8pt; font-weight: 700; }
        .report-time-note { margin-top: 1mm; color: #afbac4; font-size: 7.3pt; overflow-wrap: anywhere; }
        .report-insight { min-height: 40mm; padding: 5mm; background: var(--blue-soft); border-top: 1mm solid var(--blue); break-inside: avoid; }
        .report-insight strong { display: block; font-size: 13pt; line-height: 1.2; overflow-wrap: anywhere; }
        .report-insight p { margin: 2.5mm 0 0; color: #4e6379; font-size: 7.8pt; overflow-wrap: anywhere; }
        .report-breakdowns { display: grid; grid-template-columns: 1fr 1fr; gap: 4mm; margin-bottom: 6mm; }
        .report-breakdown { min-width: 0; padding: 4.5mm 4mm; border: 1px solid var(--line); background: #fff; }
        .report-breakdown h3 { margin: 0 0 3mm; font-size: 9pt; break-after: avoid-page; }
        .report-bar-row { display: grid; grid-template-columns: minmax(23mm, 1fr) 1.5fr 14mm; gap: 2.5mm; align-items: center; margin: 3.5mm 0; }
        .report-bar-name { min-width: 0; font-size: 7.8pt; font-weight: 650; overflow-wrap: anywhere; }
        .report-track { height: 2mm; overflow: hidden; background: #e8ecee; border-radius: 2mm; }
        .report-fill { display: block; height: 100%; background: var(--coral); border-radius: inherit; }
        .report-breakdown--category .report-fill { background: var(--teal); }
        .report-bar-count { font-size: 7.6pt; font-weight: 800; text-align: right; white-space: nowrap; }
        .report-empty { margin: 4mm 0; padding: 4mm; color: var(--muted); background: #f6f8f8; border: 1px dashed #cdd5d8; text-align: center; }
        .report-people-tickets { display: grid; grid-template-columns: .78fr 1.42fr; gap: 5mm; align-items: start; }
        .report-people-tickets--tickets-only { grid-template-columns: 1fr; }
        .report-agent-list { border-top: 1px solid var(--ink); }
        .report-agent { display: grid; grid-template-columns: 8mm minmax(0, 1fr) auto; gap: 2.5mm; align-items: center; padding: 3.3mm 0; border-bottom: 1px solid var(--line); break-inside: avoid; }
        .report-avatar { display: grid; place-items: center; width: 8mm; height: 8mm; color: #fff; background: var(--ink); border-radius: 2mm; font-size: 7pt; font-weight: 800; }
        .report-agent:nth-child(2) .report-avatar { background: var(--blue); }
        .report-agent:nth-child(3) .report-avatar { background: var(--teal); }
        .report-agent-name { font-size: 8pt; font-weight: 700; overflow-wrap: anywhere; }
        .report-agent-role { color: var(--muted); font-size: 7.2pt; overflow-wrap: anywhere; }
        .report-handled { font-size: 11pt; font-weight: 800; text-align: right; }
        .report-handled small { display: block; color: var(--muted); font-size: 6.7pt; font-weight: 600; }
        .report-ticket-box { min-width: 0; border: 1px solid var(--line); background: #fff; }
        .report-ticket-box .report-section-label { margin: 0; padding: 3mm 3.5mm; border-bottom: 1px solid var(--line); }
        .report-ticket-wrapper { overflow-x: auto; }
        .report-ticket-table { width: 100%; min-width: 690px; table-layout: fixed; border-collapse: collapse; }
        .report-ticket-table thead { display: table-header-group; }
        .report-ticket-table th { padding: 2.2mm 2mm; color: var(--muted); background: #f5f7f7; font-size: 7pt; font-weight: 750; letter-spacing: .04em; text-align: left; text-transform: uppercase; }
        .report-ticket-table td { padding: 3.3mm 2mm; border-top: 1px solid var(--line); font-size: 7.7pt; overflow-wrap: anywhere; word-break: break-word; vertical-align: top; }
        .report-ticket-table tr { break-inside: avoid; }
        .report-ticket-table th:nth-child(1) { width: 9%; }
        .report-ticket-table th:nth-child(2) { width: 31%; }
        .report-ticket-table th:nth-child(3) { width: 17%; }
        .report-ticket-table th:nth-child(4) { width: 14%; }
        .report-ticket-table th:nth-child(5) { width: 13%; }
        .report-ticket-table th:nth-child(6) { width: 16%; }
        .report-ticket-id { color: var(--blue); font-weight: 800; }
        .report-ticket-subject { font-weight: 650; }
        .report-pill { display: inline-block; padding: .7mm 1.5mm; border-radius: 3mm; font-size: 6.9pt; font-weight: 700; }
        .report-pill--priority { color: #a44334; background: var(--coral-soft); }
        .report-pill--status { color: #225aa6; background: var(--blue-soft); }
        .report-footer { display: flex; justify-content: space-between; gap: 8mm; margin: 9mm 13mm 6mm; padding-top: 2mm; color: var(--muted); border-top: 1px solid var(--line); font-size: 7.2pt; break-inside: avoid; }
        .report-footer span { min-width: 0; overflow-wrap: anywhere; }
        @media screen and (max-width: 860px) {
            .report-toolbar { width: calc(100% - 20px); margin-top: 10px; }
            .report-canvas { padding: 10px 0 0; }
            .report-sheet { width: 100%; min-height: 100vh; border: 0; box-shadow: none; }
            .report-header { padding: 8mm 7mm 5mm; }
            .report-filter-summary { margin-right: 7mm; margin-left: 7mm; }
            .report-content { padding: 0 7mm 8mm; }
            .report-hero, .report-editorial-grid, .report-people-tickets { grid-template-columns: 1fr; }
            .report-total { text-align: left; }
            .report-metadata, .report-kpis, .report-breakdowns { grid-template-columns: 1fr 1fr; }
            .report-kpi:nth-child(3) { padding-left: 0; border-left: 0; }
            .report-kpi:nth-child(n+3) { border-top: 1px solid var(--line); }
            .report-footer { margin-right: 7mm; margin-left: 7mm; }
        }
        @media screen and (max-width: 560px) {
            .report-toolbar__actions { align-items: stretch; }
            .report-toolbar__title { width: 100%; }
            .report-toolbar__buttons { width: 100%; }
            .report-button { flex: 1 1 auto; }
            .report-field, .report-field--date { flex-basis: 100%; }
            .report-filter-form > .report-button { width: 100%; }
            .report-brand-line { align-items: flex-start; }
            .report-edition { max-width: 36%; }
            .report-title { font-size: 20pt; }
            .report-metadata, .report-kpis, .report-breakdowns, .report-performance { grid-template-columns: 1fr; }
            .report-kpi + .report-kpi { padding-left: 0; border-top: 1px solid var(--line); border-left: 0; }
            .report-time + .report-time { border-top: 1px solid #43505c; border-left: 0; }
            .report-footer { display: block; }
            .report-footer span { display: block; margin-top: 1mm; }
        }
        @page { size: A4 portrait; margin: 10mm 12mm 12mm; }
        @media print {
            html, body { background: #fff; }
            body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
            .report-toolbar, .report-errors { display: none !important; }
            .report-canvas { padding: 0; }
            .report-sheet { width: auto; min-height: auto; margin: 0; border: 0; box-shadow: none; }
            .report-header { padding: 0 0 7mm; }
            .report-filter-summary { margin-right: 0; margin-left: 0; }
            .report-content { padding-right: 0; padding-bottom: 0; padding-left: 0; }
            .report-ticket-wrapper { overflow: visible; }
            .report-ticket-table { width: 100%; min-width: 0; }
            .report-footer { margin-right: 0; margin-bottom: 0; margin-left: 0; }
        }
    </style>
</head>
<body>
    <nav class="report-toolbar" aria-label="Report controls">
        <div class="report-toolbar__actions">
            <h2 class="report-toolbar__title">Report controls</h2>
            <div class="report-toolbar__buttons">
                <a class="report-button" href="{{ route('dashboard') }}">Dashboard</a>
                <a class="report-button" href="{{ route('reports.index') }}">Reset</a>
                <button class="report-button" type="button" onclick="window.print()">Print</button>
                @if($canExport)
                    <button class="report-button report-button--pdf" type="submit" form="report-export-form">Download PDF</button>
                @endif
            </div>
        </div>
        <form class="report-filter-form" action="{{ route('reports.index') }}" method="GET">
            <div class="report-field report-field--date">
                <label for="start_datetime">Start date and time</label>
                <input id="start_datetime" type="datetime-local" name="start_datetime" value="{{ old('start_datetime', $normalized['startDatetime']) }}" @error('start_datetime') aria-invalid="true" aria-describedby="start_datetime_error" @enderror>
                @error('start_datetime')<span id="start_datetime_error" class="report-field__error">{{ $message }}</span>@enderror
            </div>
            <div class="report-field report-field--date">
                <label for="end_datetime">End date and time</label>
                <input id="end_datetime" type="datetime-local" name="end_datetime" value="{{ old('end_datetime', $normalized['endDatetime']) }}" @error('end_datetime') aria-invalid="true" aria-describedby="end_datetime_error" @enderror>
                @error('end_datetime')<span id="end_datetime_error" class="report-field__error">{{ $message }}</span>@enderror
            </div>
            @if($userRole === 'administrator')
                <div class="report-field">
                    <label for="account_id">Account</label>
                    <select id="account_id" name="account_id">
                        <option value="">All accounts</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" @selected((string) old('account_id', $normalized['accountId']) === (string) $account->id)>{{ $account->name ?: 'Unknown account' }}</option>
                        @endforeach
                    </select>
                    @error('account_id')<span id="account_id_error" class="report-field__error">{{ $message }}</span>@enderror
                </div>
            @endif
            <div class="report-field">
                <label for="status_id">Status</label>
                <select id="status_id" name="status_id">
                    <option value="">All statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->id }}" @selected((string) old('status_id', $normalized['statusId']) === (string) $status->id)>{{ $status->name ?: 'Unknown status' }}</option>
                    @endforeach
                </select>
                @error('status_id')<span id="status_id_error" class="report-field__error">{{ $message }}</span>@enderror
            </div>
            <div class="report-field">
                <label for="priority_id">Priority</label>
                <select id="priority_id" name="priority_id">
                    <option value="">All priorities</option>
                    @foreach($priorities as $priority)
                        <option value="{{ $priority->id }}" @selected((string) old('priority_id', $normalized['priorityId']) === (string) $priority->id)>{{ $priority->name ?: 'Unknown priority' }}</option>
                    @endforeach
                </select>
                @error('priority_id')<span id="priority_id_error" class="report-field__error">{{ $message }}</span>@enderror
            </div>
            <button class="report-button report-button--primary" type="submit">Filter report</button>
        </form>
    </nav>

    @if($errors->any())
        <div class="report-errors" role="alert" aria-live="polite">
            <strong>The report filters could not be applied.</strong>
            <ul>
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

    <div class="report-canvas">
        <main class="report-sheet">
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
        </main>
    </div>
</body>
</html>
