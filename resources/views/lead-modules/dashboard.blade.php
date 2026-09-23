<!doctype html>
<html>
<head>
    <title>{{ $module['title'] }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 32px; background: #f6f7f9; color: #111827; }
        .topbar { display: flex; justify-content: space-between; align-items: flex-start; gap: 24px; margin-bottom: 22px; }
        .nav a, .button { display: inline-block; background: #111827; color: white; padding: 10px 14px; border-radius: 8px; text-decoration: none; margin: 0 6px 8px 0; }
        .nav a.secondary { background: white; color: #111827; border: 1px solid #d1d5db; }
        .grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px; }
        .card { background: white; padding: 18px; border: 1px solid #e5e7eb; border-radius: 8px; }
        .label { color: #6b7280; font-size: 13px; }
        .value { font-size: 28px; font-weight: 700; margin-top: 6px; }
        table { width: 100%; border-collapse: collapse; background: white; margin-top: 24px; border: 1px solid #e5e7eb; }
        th, td { padding: 10px; border-bottom: 1px solid #e5e7eb; text-align: left; vertical-align: top; }
        th { color: #374151; font-size: 13px; background: #f9fafb; }
        .status { text-transform: capitalize; }
        @media (max-width: 900px) { .topbar { display: block; } .grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        @media (max-width: 560px) { .grid { grid-template-columns: 1fr; } }
    </style>
    @include('shared.synergia-styles')
</head>
<body>
    @include('shared.synergia-nav')
    @php
        $statusLabel = fn ($status) => $module['status_options'][$status] ?? str_replace('_', ' ', $status);
    @endphp

    <div class="topbar">
        <div>
            <p class="label">SynNexus vertical</p>
            <h1>{{ $module['title'] }}</h1>
            <p>{{ $module['description'] }}</p>
        </div>
        <div class="nav">
            <a href="{{ route($module['route_name'].'.leads.create') }}">{{ $module['create_button'] }}</a>
            <a href="{{ route($module['route_name'].'.leads.index') }}">View Leads</a>
            @foreach($modules as $key => $item)
                @if($key !== $module['key'] && auth()->user()->canAccessModule($key))
                    <a class="secondary" href="{{ route($item['route_name'].'.dashboard') }}">{{ $item['short_title'] }}</a>
                @endif
            @endforeach
            @if(auth()->user()->canAccessModule('remodeling'))
                <a class="secondary" href="{{ route('remodeling.dashboard') }}">Construction CRM</a>
            @endif
        </div>
    </div>

    <div class="grid">
        <div class="card"><div class="label">Total Leads</div><div class="value">{{ $summary['total'] }}</div></div>
        <div class="card"><div class="label">Open Leads</div><div class="value">{{ $summary['open'] }}</div></div>
        <div class="card"><div class="label">New Intake</div><div class="value">{{ $summary['new'] }}</div></div>
        <div class="card"><div class="label">Won / Converted</div><div class="value">{{ $summary['won'] }}</div></div>
        <div class="card"><div class="label">{{ $module['value_label'] }}</div><div class="value">${{ number_format($summary['pipeline_value'], 2) }}</div></div>
        <div class="card"><div class="label">Average Score</div><div class="value">{{ $summary['average_score'] }}</div></div>
        @foreach($module['status_options'] as $status => $label)
            <div class="card"><div class="label">{{ $label }}</div><div class="value">{{ $summary['status_counts'][$status] ?? 0 }}</div></div>
        @endforeach
    </div>

    <h2>Recent Leads</h2>
    <table>
        <tr>
            <th>Lead</th>
            <th>Company / Contact</th>
            <th>Status</th>
            <th>Value</th>
            <th>Next Action</th>
            <th></th>
        </tr>
        @forelse($summary['recent_leads'] as $lead)
            <tr>
                <td>{{ $lead->title }}</td>
                <td>
                    {{ optional($lead->company)->name ?: trim(optional($lead->contact)->first_name.' '.optional($lead->contact)->last_name) }}
                    <div class="label">{{ optional($lead->contact)->phone }} {{ optional($lead->contact)->email }}</div>
                </td>
                <td class="status">{{ $statusLabel($lead->status) }}</td>
                <td>${{ number_format($lead->estimated_value ?? 0, 2) }}</td>
                <td>{{ $lead->next_best_action }}</td>
                <td><a href="{{ route($module['route_name'].'.leads.show', $lead) }}">Open</a></td>
            </tr>
        @empty
            <tr><td colspan="6">No leads yet. Create the first one to start this module.</td></tr>
        @endforelse
    </table>
</body>
</html>
