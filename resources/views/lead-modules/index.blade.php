<!doctype html>
<html>
<head>
    <title>{{ $module['short_title'] }} Leads</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 32px; background: #f6f7f9; color: #111827; }
        .button { display: inline-block; background: #111827; color: white; padding: 10px 14px; border-radius: 8px; text-decoration: none; margin-right: 8px; }
        table { width: 100%; border-collapse: collapse; background: white; margin-top: 20px; border: 1px solid #e5e7eb; }
        th, td { padding: 10px; border-bottom: 1px solid #e5e7eb; text-align: left; vertical-align: top; }
        th { background: #f9fafb; color: #374151; font-size: 13px; }
        .muted { color: #6b7280; font-size: 13px; }
    </style>
    @include('shared.synergia-styles')
</head>
<body>
    @include('shared.synergia-nav')
    @php
        $statusLabel = fn ($status) => $module['status_options'][$status] ?? str_replace('_', ' ', $status);
    @endphp

    <h1>{{ $module['short_title'] }} Leads</h1>
    <p>
        <a class="button" href="{{ route($module['route_name'].'.dashboard') }}">Dashboard</a>
        <a class="button" href="{{ route($module['route_name'].'.leads.create') }}">{{ $module['create_button'] }}</a>
    </p>

    <table>
        <tr>
            <th>Lead</th>
            <th>Company / Contact</th>
            <th>Status</th>
            <th>Priority</th>
            <th>Value</th>
            <th>Follow-up</th>
            <th></th>
        </tr>
        @forelse($leads as $lead)
            <tr>
                <td>{{ $lead->title }}<div class="muted">Score {{ $lead->lead_score }}</div></td>
                <td>
                    {{ optional($lead->company)->name ?: trim(optional($lead->contact)->first_name.' '.optional($lead->contact)->last_name) }}
                    <div class="muted">{{ optional($lead->contact)->phone }} {{ optional($lead->contact)->email }}</div>
                </td>
                <td>{{ $statusLabel($lead->status) }}</td>
                <td>{{ ucfirst($lead->priority) }}</td>
                <td>${{ number_format($lead->estimated_value ?? 0, 2) }}</td>
                <td>{{ optional($lead->next_follow_up_at)->format('M j, Y g:i A') }}</td>
                <td><a href="{{ route($module['route_name'].'.leads.show', $lead) }}">Open</a></td>
            </tr>
        @empty
            <tr><td colspan="7">No leads found.</td></tr>
        @endforelse
    </table>

    {{ $leads->links() }}
</body>
</html>
