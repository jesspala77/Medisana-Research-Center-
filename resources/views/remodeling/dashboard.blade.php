<!doctype html>
<html>
<head>
    <title>Remodeling Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 32px; background: #f7f7f7; }
        .grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
        .card { background: white; padding: 20px; border-radius: 8px; }
        .value { font-size: 28px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; background: white; margin-top: 24px; }
        th, td { padding: 10px; border-bottom: 1px solid #ddd; text-align: left; }
        .button { background: #111827; color: white; padding: 10px 14px; border-radius: 8px; text-decoration: none; display: inline-block; margin: 0 4px 8px 0; }
        @media (max-width: 900px) { .grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 560px) { .grid { grid-template-columns: 1fr; } }
    </style>
    @include('shared.synergia-styles')
</head>
<body>
    @include('shared.synergia-nav')

    <h1>Remodeling Command Dashboard</h1>

    <p>
        <a class="button" href="{{ route('remodeling.projects.create') }}">Create Project</a>
        <a class="button" href="{{ route('remodeling.projects.index') }}">View Projects</a>
        @if(auth()->user()->canAccessModule('capital-funding'))
            <a class="button" href="{{ route('capital-funding.dashboard') }}">Capital Funding</a>
        @endif
        @if(auth()->user()->canAccessModule('bond-agency'))
            <a class="button" href="{{ route('bond-agency.dashboard') }}">Bond Agency</a>
        @endif
        @if(auth()->user()->canAccessModule('clinical-recruitment'))
            <a class="button" href="{{ route('clinical-recruitment.dashboard') }}">Clinical Recruitment</a>
        @endif
        @if(auth()->user()->canAccessModule('cnc-quote'))
            <a class="button" href="{{ route('cnc-quote.dashboard') }}">CNC Quote</a>
        @endif
    </p>

    <div class="grid">
        <div class="card"><div>Projects</div><div class="value">{{ $summary['projects_total'] }}</div></div>
        <div class="card"><div>Active</div><div class="value">{{ $summary['active_projects'] }}</div></div>
        <div class="card"><div>New Leads</div><div class="value">{{ $summary['new_leads'] }}</div></div>
        <div class="card"><div>Site Visits</div><div class="value">{{ $summary['site_visits_scheduled'] }}</div></div>
        <div class="card"><div>Draft Estimates</div><div class="value">{{ $summary['estimates_draft'] }}</div></div>
        <div class="card"><div>Sent Estimate Value</div><div class="value">${{ number_format($summary['estimates_sent_value'], 2) }}</div></div>
        <div class="card"><div>Pending Proposals</div><div class="value">${{ number_format($summary['proposals_pending_value'], 2) }}</div></div>
        <div class="card"><div>Signed Contracts</div><div class="value">${{ number_format($summary['contracts_signed_value'], 2) }}</div></div>
    </div>

    <h2>Recent Projects</h2>

    <table>
        <tr>
            <th>Project</th>
            <th>Customer</th>
            <th>Stage</th>
            <th>Action</th>
        </tr>
        @foreach($summary['recent_projects'] as $project)
            <tr>
                <td>{{ $project->project_name }}</td>
                <td>{{ optional($project->contact)->first_name }} {{ optional($project->contact)->last_name }}</td>
                <td>{{ str_replace('_', ' ', $project->stage) }}</td>
                <td><a href="{{ route('remodeling.projects.show', $project) }}">Open</a></td>
            </tr>
        @endforeach
    </table>
</body>
</html>
