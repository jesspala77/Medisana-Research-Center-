<!doctype html>
<html>
<head>
    <title>Clinical Research Regulatory Services</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 32px; background: #f6f7f9; color: #111827; }
        .topbar { display: flex; justify-content: space-between; align-items: flex-start; gap: 24px; margin-bottom: 22px; }
        .actions { display: flex; flex-wrap: wrap; gap: 8px; }
        .button { display: inline-block; background: #111827; color: white; padding: 10px 14px; border-radius: 8px; text-decoration: none; }
        .button.secondary { background: white; color: #111827; border: 1px solid #d1d5db; }
        .grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px; }
        .service-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; margin-top: 16px; }
        .card, .panel { background: white; padding: 18px; border: 1px solid #e5e7eb; border-radius: 8px; }
        .panel { margin-top: 24px; }
        .label, .meta { color: #6b7280; font-size: 13px; }
        .meta { font-weight: 800; text-transform: uppercase; }
        .value { font-size: 28px; font-weight: 700; margin-top: 6px; }
        .resource-list { margin: 8px 0 0; padding-left: 18px; }
        .resource-list li { margin-bottom: 6px; }
        .workflow-strip { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 10px; margin-top: 14px; }
        .workflow-step { background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; }
        .workflow-step strong { display: block; color: #1d4ed8; font-size: 12px; margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; background: white; margin-top: 14px; border: 1px solid #e5e7eb; }
        th, td { padding: 10px; border-bottom: 1px solid #e5e7eb; text-align: left; vertical-align: top; }
        th { color: #374151; font-size: 13px; background: #f9fafb; }
        @media (max-width: 900px) { .topbar { display: block; } .grid, .service-grid, .workflow-strip { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        @media (max-width: 560px) { .grid, .service-grid, .workflow-strip { grid-template-columns: 1fr; } }
    </style>
    @include('shared.synergia-styles')
</head>
<body>
    @include('shared.synergia-nav')
    @php
        $statusLabel = fn ($status) => $statuses[$status] ?? str_replace('_', ' ', $status);
        $serviceLabel = fn ($category) => $services[$category]['title'] ?? str_replace('_', ' ', $category);
    @endphp

    <div class="topbar">
        <div>
            <p class="label">Clinical research operations</p>
            <h1>Regulatory Services</h1>
            <p>Separate regulatory service records for client, site, sponsor, protocol, credentialing, IRB, SOP, audit, CAPA, and startup work.</p>
        </div>
        <div class="actions">
            <a class="button" href="{{ route('clinical-regulatory.records.create') }}">Create Regulatory Record</a>
            <a class="button secondary" href="{{ route('clinical-regulatory.records.index') }}">View Records</a>
            <a class="button secondary" href="{{ route('clinical-recruitment.dashboard') }}">Recruitment Dashboard</a>
        </div>
    </div>

    <section class="grid" aria-label="Regulatory service KPIs">
        <div class="card"><div class="label">Total Records</div><div class="value">{{ $summary['total'] }}</div></div>
        <div class="card"><div class="label">Open</div><div class="value">{{ $summary['open'] }}</div></div>
        <div class="card"><div class="label">Needs Client Action</div><div class="value">{{ $summary['needs_client_action'] }}</div></div>
        <div class="card"><div class="label">Ready</div><div class="value">{{ $summary['ready'] }}</div></div>
        <div class="card"><div class="label">Complete</div><div class="value">{{ $summary['complete'] }}</div></div>
        <div class="card"><div class="label">Due Soon</div><div class="value">{{ $summary['due_soon'] }}</div></div>
        <div class="card"><div class="label">Overdue</div><div class="value">{{ $summary['overdue'] }}</div></div>
        <div class="card"><div class="label">Service Lines</div><div class="value">{{ count($services) }}</div></div>
    </section>

    <section class="panel" id="services">
        <div class="meta">Service Menu</div>
        <h2>Choose a Regulatory Service</h2>
        <div class="service-grid">
            @foreach($services as $key => $service)
                <article class="card">
                    <div class="meta">Regulatory Service</div>
                    <h3>{{ $service['title'] }}</h3>
                    <p>{{ $service['summary'] }}</p>
                    <ul class="resource-list">
                        @foreach($service['tools'] as $tool)
                            <li>{{ $tool }}</li>
                        @endforeach
                    </ul>
                    <p><a class="button" href="{{ route('clinical-regulatory.records.create', ['service_category' => $key]) }}">Start Record</a></p>
                </article>
            @endforeach
        </div>
    </section>

    <section class="panel" id="tool-library">
        <div class="meta">Tool Library</div>
        <h2>Reusable Templates</h2>
        <div class="service-grid">
            @foreach($resources as $resource)
                <article class="card">
                    <div class="meta">{{ $resource['type'] }}</div>
                    <h3>{{ $resource['title'] }}</h3>
                    <p>{{ $resource['description'] }}</p>
                    <p class="label">{{ $resource['path'] }}</p>
                </article>
            @endforeach
        </div>
    </section>

    <section class="panel" id="workflow">
        <div class="meta">Workflow</div>
        <h2>How to Use Regulatory Services</h2>
        <div class="workflow-strip">
            @foreach($workflowSteps as $index => $step)
                <div class="workflow-step">
                    <strong>Step {{ $index + 1 }}</strong>
                    {{ $step }}
                </div>
            @endforeach
        </div>
    </section>

    <section class="panel">
        <div class="meta">Recent Records</div>
        <h2>Latest Regulatory Work</h2>
        <table>
            <tr>
                <th>Request</th>
                <th>Client / Protocol</th>
                <th>Service</th>
                <th>Status</th>
                <th>Due</th>
                <th></th>
            </tr>
            @forelse($summary['recent_records'] as $record)
                <tr>
                    <td>{{ $record->request_title }}<div class="label">{{ ucfirst($record->priority) }}</div></td>
                    <td>{{ $record->client_name }}<div class="label">{{ $record->protocol ?: '-' }}</div></td>
                    <td>{{ $serviceLabel($record->service_category) }}</td>
                    <td>{{ $statusLabel($record->status) }}</td>
                    <td>{{ optional($record->due_date)->format('M j, Y') ?: '-' }}</td>
                    <td><a href="{{ route('clinical-regulatory.records.show', $record) }}">Open</a></td>
                </tr>
            @empty
                <tr><td colspan="6">No regulatory service records yet. Start with a service card above.</td></tr>
            @endforelse
        </table>
    </section>
</body>
</html>
