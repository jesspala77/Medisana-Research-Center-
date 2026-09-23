<!doctype html>
<html>
<head>
    <title>{{ $lead->title }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 32px; background: #f6f7f9; color: #111827; }
        .grid { display: grid; grid-template-columns: 1.2fr .8fr; gap: 18px; }
        .card { background: white; padding: 18px; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 18px; }
        label { display: block; margin-top: 12px; font-weight: 700; }
        input, select { width: 100%; box-sizing: border-box; padding: 9px; margin-top: 4px; border: 1px solid #d1d5db; border-radius: 6px; }
        button { margin-top: 16px; background: #111827; color: white; padding: 10px 14px; border: 0; border-radius: 8px; cursor: pointer; }
        .workflow-actions { display: flex; flex-wrap: wrap; gap: 8px; margin: 10px 0 18px; }
        .workflow-actions form { margin: 0; }
        .workflow-actions button { margin: 0; background: #1f2937; }
        dl { display: grid; grid-template-columns: 180px 1fr; gap: 8px 14px; }
        dt { color: #6b7280; }
        dd { margin: 0; }
        .muted { color: #6b7280; }
        @media (max-width: 850px) { .grid { grid-template-columns: 1fr; } dl { grid-template-columns: 1fr; } }
    </style>
    @include('shared.synergia-styles')
</head>
<body>
    @include('shared.synergia-nav')
    @php
        $statusLabel = fn ($status) => $module['status_options'][$status] ?? str_replace('_', ' ', $status);
    @endphp

    <p>
        <a href="{{ route($module['route_name'].'.dashboard') }}">Dashboard</a> |
        <a href="{{ route($module['route_name'].'.leads.index') }}">Leads</a>
    </p>

    <div class="grid">
        <main>
            <div class="card">
                <p class="muted">{{ $module['short_title'] }}</p>
                <h1>{{ $lead->title }}</h1>
                <dl>
                    <dt>Status</dt><dd>{{ $statusLabel($lead->status) }}</dd>
                    <dt>Priority</dt><dd>{{ ucfirst($lead->priority) }}</dd>
                    <dt>Estimated Value</dt><dd>${{ number_format($lead->estimated_value ?? 0, 2) }}</dd>
                    <dt>Lead Score</dt><dd>{{ $lead->lead_score }}</dd>
                    <dt>Next Follow-up</dt><dd>{{ optional($lead->next_follow_up_at)->format('M j, Y g:i A') }}</dd>
                    <dt>Next Best Action</dt><dd>{{ $lead->next_best_action }}</dd>
                </dl>
                @if($lead->summary)
                    <h3>Summary</h3>
                    <p>{{ $lead->summary }}</p>
                @endif
            </div>

            <div class="card">
                <h2>Module Profile</h2>
                <dl>
                    @foreach($module['profile_fields'] as $field)
                        @php $value = $profile?->{$field['name']}; @endphp
                        <dt>{{ $field['label'] }}</dt>
                        <dd>
                            @if($field['type'] === 'checkbox')
                                {{ $value ? 'Yes' : 'No' }}
                            @elseif($value instanceof \Illuminate\Support\Carbon)
                                {{ $value->format('M j, Y') }}
                            @else
                                {{ $value ?: '-' }}
                            @endif
                        </dd>
                    @endforeach
                </dl>
            </div>
        </main>

        <aside>
            <div class="card">
                <h2>Contact</h2>
                <dl>
                    <dt>Company</dt><dd>{{ optional($lead->company)->name ?: '-' }}</dd>
                    <dt>Name</dt><dd>{{ trim(optional($lead->contact)->first_name.' '.optional($lead->contact)->last_name) ?: '-' }}</dd>
                    <dt>Phone</dt><dd>{{ optional($lead->contact)->phone ?: '-' }}</dd>
                    <dt>Email</dt><dd>{{ optional($lead->contact)->email ?: '-' }}</dd>
                </dl>
            </div>

            <div class="card">
                <h2>Update Workflow</h2>
                @if(auth()->user()?->isPlatformAdmin() && $workflowActions->isNotEmpty())
                    <div class="workflow-actions">
                        @foreach($workflowActions as $action)
                            <form method="POST" action="{{ route('leads.workflow.apply', [$lead, $action]) }}">
                                @csrf
                                <button type="submit">{{ $action->button_label }}</button>
                            </form>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route($module['route_name'].'.leads.status', $lead) }}">
                    @csrf
                    <label>Status</label>
                    <select name="status">
                        @foreach($module['status_options'] as $status => $label)
                            <option value="{{ $status }}" @selected($lead->status === $status)>{{ $label }}</option>
                        @endforeach
                    </select>

                    <label>Priority</label>
                    <select name="priority">
                        @foreach(['low', 'normal', 'high', 'urgent'] as $priority)
                            <option value="{{ $priority }}" @selected($lead->priority === $priority)>{{ ucfirst($priority) }}</option>
                        @endforeach
                    </select>

                    <label>Next Follow-up</label>
                    <input name="next_follow_up_at" type="datetime-local" value="{{ $lead->next_follow_up_at?->format('Y-m-d\TH:i') }}">

                    <label>Next Best Action</label>
                    <input name="next_best_action" value="{{ $lead->next_best_action }}">

                    <button>Update Lead</button>
                </form>
            </div>
        </aside>
    </div>
</body>
</html>
