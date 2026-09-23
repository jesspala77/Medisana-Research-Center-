<!doctype html>
<html>
<head>
    <title>{{ $program['name'] }} Program</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 32px; background: #f6f7f9; color: #111827; }
        .hero { display: grid; grid-template-columns: minmax(0, 1fr) auto; gap: 18px; align-items: start; margin-bottom: 20px; }
        .panel { background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 18px; margin-bottom: 16px; }
        .muted { color: #6b7280; }
        .kicker { color: #6b7280; font-size: 12px; font-weight: 800; text-transform: uppercase; }
        .status { display: inline-flex; border: 1px solid #d1d5db; border-radius: 999px; padding: 5px 10px; font-size: 12px; font-weight: 800; }
        .actions { display: flex; flex-wrap: wrap; gap: 8px; }
        .button { display: inline-flex; align-items: center; justify-content: center; min-height: 40px; background: #111827; color: white; border-radius: 8px; padding: 9px 13px; text-decoration: none; }
        .button.secondary { background: white; color: #111827; border: 1px solid #d1d5db; }
        .grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
        .metrics { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 10px; margin-top: 14px; }
        .metric { border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; background: #f9fafb; }
        .metric span { display: block; color: #6b7280; font-size: 11px; font-weight: 800; text-transform: uppercase; }
        .metric strong { display: block; margin-top: 5px; font-size: 24px; }
        table { width: 100%; border-collapse: collapse; background: white; border: 1px solid #e5e7eb; }
        th, td { padding: 10px; border-bottom: 1px solid #e5e7eb; text-align: left; vertical-align: top; }
        th { background: #f9fafb; color: #374151; font-size: 12px; text-transform: uppercase; }
        @media (max-width: 900px) { .hero, .grid { grid-template-columns: 1fr; } .metrics { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        @media (max-width: 560px) { .metrics { grid-template-columns: 1fr; } }
    </style>
    @include('shared.synergia-styles')
</head>
<body>
    @include('shared.synergia-nav')

    <div class="hero">
        <div>
            <p><a href="{{ route('dashboard') }}">Command Center</a></p>
            <div class="kicker">{{ $program['division'] }}</div>
            <h1>{{ $program['name'] }} Program</h1>
            <p>{{ $program['summary'] }}</p>
        </div>
        <span class="status">{{ $program['status'] }}</span>
    </div>

    <section class="panel">
        <div class="kicker">Client Focus</div>
        <p>{{ $program['client_focus'] }}</p>
        <div class="actions">
            @foreach($actions as $index => $action)
                <a class="button {{ $index === 0 ? '' : 'secondary' }}" href="{{ $action['href'] }}">{{ $action['label'] }}</a>
            @endforeach
        </div>
    </section>

    <section class="grid">
        @forelse($moduleCards as $card)
            <article class="panel">
                <div class="kicker">{{ $card['module'] }}</div>
                <h2>{{ $card['title'] }}</h2>
                <p><a href="{{ $card['dashboard_route'] }}">Open full dashboard</a></p>
                <div class="metrics">
                    @foreach($card['metrics'] as $metric)
                        <div class="metric">
                            <span>{{ $metric['label'] }}</span>
                            <strong>{{ $metric['value'] }}</strong>
                        </div>
                    @endforeach
                </div>
            </article>
        @empty
            <article class="panel">
                <h2>Enterprise Program</h2>
                <p class="muted">This program is reserved for platform administration and operating-system oversight.</p>
            </article>
        @endforelse

        <article class="panel">
            <div class="kicker">Program Capabilities</div>
            <h2>Available Workspace Areas</h2>
            <div class="metrics">
                <div class="metric"><span>Dashboards</span><strong>{{ count($moduleCards) ?: 1 }}</strong></div>
                <div class="metric"><span>Actions</span><strong>{{ count($actions) }}</strong></div>
                <div class="metric"><span>Agents</span><strong>{{ count($agents) }}</strong></div>
            </div>
            <p class="muted">Use this page as the client-facing program hub for dashboards, KPIs, agents, workflow queues, and module operations.</p>
        </article>
    </section>

    <section class="panel">
        <div class="kicker">Agents & Contacts</div>
        <h2>Program Directory</h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Context</th>
                <th>Email</th>
                <th>Phone</th>
            </tr>
            @forelse($agents as $agent)
                <tr>
                    <td>{{ $agent['name'] }}</td>
                    <td>{{ $agent['context'] }}</td>
                    <td>{{ $agent['email'] ?: '-' }}</td>
                    <td>{{ $agent['phone'] ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="muted">No agents or contacts have been added to this program yet.</td>
                </tr>
            @endforelse
        </table>
    </section>
</body>
</html>
