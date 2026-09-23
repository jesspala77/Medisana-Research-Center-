@php
    $user = auth()->user();
    $canModule = fn (string $module) => $user?->canAccessModule($module) ?? false;
    $isAdmin = $user?->isPlatformAdmin() ?? false;

    $navItems = [
        ['label' => 'Construction', 'route' => 'remodeling.dashboard', 'active' => 'remodeling.*', 'meta' => 'Projects', 'module' => 'remodeling'],
        ['label' => 'Capital Funding', 'route' => 'capital-funding.dashboard', 'active' => 'capital-funding.*', 'meta' => 'Deals', 'module' => 'capital-funding'],
        ['label' => 'Bond Agency', 'route' => 'bond-agency.dashboard', 'active' => ['bond-agency.dashboard', 'bond-agency.leads.*', 'bond-agency.import*'], 'meta' => 'Policies', 'module' => 'bond-agency'],
        ['label' => 'Outreach', 'route' => 'bond-agency.outreach.index', 'active' => 'bond-agency.outreach.*', 'meta' => 'Drafts', 'module' => 'bond-agency'],
        ['label' => 'Clinical', 'route' => 'clinical-recruitment.dashboard', 'active' => 'clinical-recruitment.*', 'meta' => 'Candidates', 'module' => 'clinical-recruitment'],
        ['label' => 'Regulatory', 'route' => 'clinical-regulatory.dashboard', 'active' => 'clinical-regulatory.*', 'meta' => 'Services', 'module' => 'clinical-recruitment'],
        ['label' => 'CNC Quote', 'route' => 'cnc-quote.dashboard', 'active' => 'cnc-quote.*', 'meta' => 'RFQs', 'module' => 'cnc-quote'],
    ];

    $navItems = array_values(array_filter($navItems, fn ($item) => $canModule($item['module'])));

    if ($isAdmin) {
        $navItems[] = ['label' => 'Command Queue', 'route' => 'command-queue.index', 'active' => 'command-queue.*', 'meta' => 'Priority'];
        $navItems[] = ['label' => 'User Access', 'route' => 'admin.user-access.index', 'active' => 'admin.user-access.*', 'meta' => 'Security'];
    }

    $actionItems = [
        ['label' => 'New Project', 'route' => 'remodeling.projects.create', 'module' => 'remodeling'],
        ['label' => 'New Funding Lead', 'route' => 'capital-funding.leads.create', 'module' => 'capital-funding'],
        ['label' => 'New Bond Lead', 'route' => 'bond-agency.leads.create', 'module' => 'bond-agency'],
        ['label' => 'Import Agencies', 'route' => 'bond-agency.import', 'module' => 'bond-agency'],
        ['label' => 'Outreach Queue', 'route' => 'bond-agency.outreach.index', 'module' => 'bond-agency'],
        ['label' => 'New Candidate', 'route' => 'clinical-recruitment.leads.create', 'module' => 'clinical-recruitment'],
        ['label' => 'Regulatory Record', 'route' => 'clinical-regulatory.records.create', 'module' => 'clinical-recruitment'],
        ['label' => 'New CNC Lead', 'route' => 'cnc-quote.leads.create', 'module' => 'cnc-quote'],
    ];

    $actionItems = array_values(array_filter($actionItems, fn ($item) => $canModule($item['module'])));

    $currentTitle = isset($module)
        ? $module['short_title']
        : match (true) {
            request()->routeIs('remodeling.*') => 'Construction CRM',
            request()->routeIs('bond-agency.outreach.*') => 'Outreach Operations',
            request()->routeIs('clinical-regulatory.*') => 'Regulatory Services',
            request()->routeIs('command-queue.*') => 'Command Queue',
            default => 'SynNexus',
        };
@endphp

<aside class="sy-rail">
    <a class="sy-brand" href="{{ route('dashboard') }}">
        <img src="{{ asset('images/brand/GSG%20ICON%20LOGO.png') }}" alt="Global Synergia Group" class="sy-brand-logo sy-brand-logo--gsg" />
        <img src="{{ asset('images/brand/SynNexus%20Icon%20Logo.png') }}" alt="SynNexus" class="sy-brand-logo sy-brand-logo--synnexus" />
    </a>

    <nav class="sy-nav" aria-label="Primary navigation">
        @foreach($navItems as $item)
            <a href="{{ route($item['route']) }}" @class(['active' => request()->routeIs(...(array) $item['active'])])>
                <span>{{ $item['label'] }}</span>
                <small>{{ $item['meta'] }}</small>
            </a>
        @endforeach
    </nav>
</aside>

<header class="sy-command-bar">
    <div>
        <span class="sy-kicker">Agency development command center</span>
        <strong>{{ $currentTitle }}</strong>
    </div>
    <div class="sy-command-actions">
        @foreach($actionItems as $item)
            <a href="{{ route($item['route']) }}">{{ $item['label'] }}</a>
        @endforeach
        @if($isAdmin)
            <a href="{{ route('admin.user-access.index') }}">Manage User Access</a>
        @endif
    </div>
</header>
