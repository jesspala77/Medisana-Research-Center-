<!doctype html>
<html>
<head>
    <title>Regulatory Service Records</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 32px; background: #f6f7f9; color: #111827; }
        .button { display: inline-block; background: #111827; color: white; padding: 10px 14px; border-radius: 8px; text-decoration: none; margin-right: 8px; }
        .button.secondary { background: white; color: #111827; border: 1px solid #d1d5db; }
        .filters { display: flex; flex-wrap: wrap; gap: 8px; margin: 16px 0; }
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
        $statusLabel = fn ($status) => $statuses[$status] ?? str_replace('_', ' ', $status);
        $serviceLabel = fn ($category) => $services[$category]['title'] ?? str_replace('_', ' ', $category);
    @endphp

    <h1>Regulatory Service Records</h1>
    <p>
        <a class="button" href="{{ route('clinical-regulatory.dashboard') }}">Dashboard</a>
        <a class="button" href="{{ route('clinical-regulatory.records.create') }}">Create Regulatory Record</a>
        <a class="button secondary" href="{{ route('clinical-recruitment.dashboard') }}">Recruitment Dashboard</a>
    </p>

    <div class="filters">
        @foreach($statuses as $status => $label)
            <a href="{{ route('clinical-regulatory.records.index', ['status' => $status]) }}">{{ $label }}</a>
        @endforeach
    </div>

    <table>
        <tr>
            <th>Request</th>
            <th>Client / Contact</th>
            <th>Service</th>
            <th>Status</th>
            <th>Priority</th>
            <th>Due</th>
            <th></th>
        </tr>
        @forelse($records as $record)
            <tr>
                <td>{{ $record->request_title }}<div class="muted">{{ $record->protocol ?: '-' }}</div></td>
                <td>{{ $record->client_name }}<div class="muted">{{ $record->primary_contact_name ?: '-' }}</div></td>
                <td>{{ $serviceLabel($record->service_category) }}</td>
                <td>{{ $statusLabel($record->status) }}</td>
                <td>{{ ucfirst($record->priority) }}</td>
                <td>{{ optional($record->due_date)->format('M j, Y') ?: '-' }}</td>
                <td><a href="{{ route('clinical-regulatory.records.show', $record) }}">Open</a></td>
            </tr>
        @empty
            <tr><td colspan="7">No regulatory service records found.</td></tr>
        @endforelse
    </table>

    {{ $records->links() }}
</body>
</html>
