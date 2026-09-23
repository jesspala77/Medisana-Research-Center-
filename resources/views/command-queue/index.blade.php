<!doctype html>
<html>
<head>
    <title>Command Queue</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 32px; background: #f6f7f9; color: #111827; }
        .queue-shell { max-width: 1280px; margin: 0 auto; }
        .queue-toolbar { display: flex; justify-content: space-between; align-items: flex-end; gap: 18px; margin-bottom: 18px; }
        .queue-toolbar p { margin: 0 0 4px; }
        .summary-grid { display: grid; grid-template-columns: repeat(4, minmax(150px, 1fr)); gap: 12px; margin: 18px 0; }
        .summary-card { background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 14px; box-shadow: 0 8px 22px rgba(16, 24, 32, .06); }
        .summary-card span { display: block; color: #6b7280; font-size: 12px; font-weight: 800; text-transform: uppercase; }
        .summary-card strong { display: block; font-size: 26px; line-height: 1.1; margin-top: 6px; }
        .queue-panel { background: white; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; box-shadow: 0 8px 22px rgba(16, 24, 32, .06); }
        .queue-table { width: 100%; border: 0; border-collapse: collapse; margin: 0; box-shadow: none; }
        .queue-table th, .queue-table td { padding: 13px 14px; border-bottom: 1px solid #e5e7eb; text-align: left; vertical-align: top; }
        .queue-table th { background: #f9fafb; color: #475467; font-size: 12px; text-transform: uppercase; }
        .queue-table tr:last-child td { border-bottom: 0; }
        .lead-title { color: #111827; font-weight: 800; }
        .muted { color: #6b7280; font-size: 13px; }
        .status, .priority { display: inline-flex; align-items: center; min-height: 26px; border-radius: 999px; padding: 3px 9px; font-size: 12px; font-weight: 800; }
        .status { background: #eef2ff; color: #3730a3; }
        .priority { background: #f1f5f9; color: #334155; }
        .priority--urgent { background: #ffe4e6; color: #9f1239; }
        .priority--high { background: #fef3c7; color: #92400e; }
        .score { color: #111827; font-variant-numeric: tabular-nums; font-weight: 800; margin-top: 6px; }
        .past-due { color: #b42318; font-weight: 800; }
        .actions { white-space: nowrap; }
        .actions a { display: inline-flex; align-items: center; min-height: 34px; border: 1px solid #d1d5db; border-radius: 8px; padding: 7px 10px; background: #fff; color: #111827; text-decoration: none; }
        .empty { padding: 24px; color: #6b7280; }
        @media (max-width: 980px) {
            .queue-toolbar { display: block; }
            .summary-grid { grid-template-columns: repeat(2, minmax(150px, 1fr)); }
        }
    </style>
    @include('shared.synergia-styles')
</head>
<body>
    @include('shared.synergia-nav')

    @php
        $statusLabels = [
            'new_request' => 'New Request',
            'docs_needed' => 'Docs Needed',
            'underwriting_ready' => 'Underwriting Ready',
            'offer_sent' => 'Offer Sent',
            'new_agency' => 'New Agency',
            'coverage_gap_found' => 'Coverage Gap Found',
            'review_scheduled' => 'Review Scheduled',
            'quote_sent' => 'Quote Sent',
            'new_candidate' => 'New Candidate',
            'prescreening' => 'Prescreening',
            'visit_scheduled' => 'Visit Scheduled',
            'screen_failed' => 'Screen Failed',
        ];

        $moduleRoutes = [
            'business-capital-funding' => 'capital-funding.leads.show',
            'bond-agency-insurance' => 'bond-agency.leads.show',
            'clinical-research' => 'clinical-recruitment.leads.show',
        ];

        $totalValue = $queue->sum(fn ($lead) => (float) ($lead->estimated_value ?? 0));
        $pastDueCount = $queue->filter(fn ($lead) => $lead->next_follow_up_at?->isPast())->count();
        $slaRiskCount = $queue->whereIn('sla_status', ['warning', 'overdue'])->count();
        $highPriorityCount = $queue->whereIn('priority', ['high', 'urgent'])->count();
    @endphp

    <main class="queue-shell">
        <div class="queue-toolbar">
            <div>
                <p class="muted">SynNexus priority workspace</p>
                <h1>Command Queue</h1>
            </div>
            <a class="button" href="{{ route('command-queue.index', ['format' => 'json']) }}">JSON Feed</a>
        </div>

        <section class="summary-grid" aria-label="Command queue summary">
            <div class="summary-card">
                <span>Open Leads</span>
                <strong>{{ $queue->count() }}</strong>
            </div>
            <div class="summary-card">
                <span>Past Due</span>
                <strong>{{ $pastDueCount }}</strong>
            </div>
            <div class="summary-card">
                <span>SLA Risk</span>
                <strong>{{ $slaRiskCount }}</strong>
            </div>
            <div class="summary-card">
                <span>Pipeline</span>
                <strong>${{ number_format($totalValue, 0) }}</strong>
            </div>
        </section>

        <section class="queue-panel">
            @if($queue->isNotEmpty())
                <table class="queue-table">
                    <thead>
                        <tr>
                            <th>Lead</th>
                            <th>Company / Contact</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Next Action</th>
                            <th>Follow-up</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($queue as $lead)
                            @php
                                $routeName = $moduleRoutes[$lead->industry?->slug] ?? null;
                                $leadUrl = $routeName ? route($routeName, $lead) : null;
                                $contactName = trim(($lead->contact?->first_name ?? '').' '.($lead->contact?->last_name ?? ''));
                                $companyOrContact = $lead->company?->name ?: ($contactName ?: 'No linked company');
                                $statusText = $statusLabels[$lead->status] ?? ucwords(str_replace('_', ' ', $lead->status ?? 'unknown'));
                                $stageText = $lead->stage ? ucwords(str_replace('_', ' ', $lead->stage)) : 'No stage';
                                $priority = $lead->priority ?: 'normal';
                                $followUpPastDue = $lead->next_follow_up_at?->isPast();
                            @endphp

                            <tr>
                                <td>
                                    <div class="lead-title">{{ $lead->title }}</div>
                                    <div class="muted">{{ $lead->industry?->name ?: 'Unassigned industry' }}</div>
                                </td>
                                <td>
                                    {{ $companyOrContact }}
                                    <div class="muted">
                                        {{ $lead->contact?->phone ?: 'No phone' }}
                                        @if($lead->contact?->email)
                                            | {{ $lead->contact->email }}
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="status">{{ $statusText }}</span>
                                    <div class="muted">{{ $stageText }}</div>
                                </td>
                                <td>
                                    <span @class(['priority', 'priority--urgent' => $priority === 'urgent', 'priority--high' => $priority === 'high'])>
                                        {{ ucfirst($priority) }}
                                    </span>
                                    <div class="score">{{ number_format($lead->command_priority_score ?? 0, 1) }}</div>
                                </td>
                                <td>{{ $lead->next_best_action ?: 'Move lead to the next workflow step' }}</td>
                                <td @class(['past-due' => $followUpPastDue])>
                                    {{ $lead->next_follow_up_at?->format('M j, Y g:i A') ?: 'Not set' }}
                                    @if($lead->sla_status)
                                        <div class="muted">SLA: {{ ucwords(str_replace('_', ' ', $lead->sla_status)) }}</div>
                                    @endif
                                </td>
                                <td class="actions">
                                    @if($leadUrl)
                                        <a href="{{ $leadUrl }}">Open</a>
                                    @else
                                        <span class="muted">No module</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty">No active leads are in the command queue.</div>
            @endif
        </section>
    </main>
</body>
</html>
