<!doctype html>
<html>
<head>
    <title>{{ $record->request_title }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 32px; background: #f6f7f9; color: #111827; }
        .grid { display: grid; grid-template-columns: 1.2fr .8fr; gap: 18px; }
        .card { background: white; padding: 18px; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 18px; }
        label { display: block; margin-top: 12px; font-weight: 700; }
        input, select, textarea { width: 100%; box-sizing: border-box; padding: 9px; margin-top: 4px; border: 1px solid #d1d5db; border-radius: 6px; }
        button { margin-top: 16px; background: #111827; color: white; padding: 10px 14px; border: 0; border-radius: 8px; cursor: pointer; }
        dl { display: grid; grid-template-columns: 190px 1fr; gap: 8px 14px; }
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
        $statusLabel = fn ($status) => $statuses[$status] ?? str_replace('_', ' ', $status);
        $serviceLabel = fn ($category) => $services[$category]['title'] ?? str_replace('_', ' ', $category);
    @endphp

    <p>
        <a href="{{ route('clinical-regulatory.dashboard') }}">Dashboard</a> |
        <a href="{{ route('clinical-regulatory.records.index') }}">Records</a>
    </p>

    @if (session('success'))
        <div class="card" style="border-color:#10b981;color:#065f46;">{{ session('success') }}</div>
    @endif

    <div class="grid">
        <main>
            <div class="card">
                <p class="muted">{{ $serviceLabel($record->service_category) }}</p>
                <h1>{{ $record->request_title }}</h1>
                <dl>
                    <dt>Client</dt><dd>{{ $record->client_name }}</dd>
                    <dt>Site</dt><dd>{{ $record->site_name ?: '-' }}</dd>
                    <dt>Sponsor / CRO</dt><dd>{{ $record->sponsor ?: '-' }}</dd>
                    <dt>Protocol / Study</dt><dd>{{ $record->protocol ?: '-' }}</dd>
                    <dt>Status</dt><dd>{{ $statusLabel($record->status) }}</dd>
                    <dt>Priority</dt><dd>{{ ucfirst($record->priority) }}</dd>
                    <dt>Due Date</dt><dd>{{ optional($record->due_date)->format('M j, Y') ?: '-' }}</dd>
                    <dt>Owner</dt><dd>{{ $record->regulatory_owner ?: '-' }}</dd>
                    <dt>Next Step</dt><dd>{{ $record->next_step ?: '-' }}</dd>
                </dl>
            </div>

            <div class="card">
                <h2>Documents & Notes</h2>
                <dl>
                    <dt>Documents Available</dt><dd>{{ $record->documents_available ?: '-' }}</dd>
                    <dt>Missing Documents</dt><dd>{{ $record->missing_documents ?: '-' }}</dd>
                    <dt>Current Blocker</dt><dd>{{ $record->current_blocker ?: '-' }}</dd>
                    <dt>Approval Path</dt><dd>{{ $record->approval_path ?: '-' }}</dd>
                    <dt>Notes</dt><dd>{{ $record->notes ?: '-' }}</dd>
                </dl>
            </div>
        </main>

        <aside>
            <div class="card">
                <h2>Contact</h2>
                <dl>
                    <dt>Name</dt><dd>{{ $record->primary_contact_name ?: '-' }}</dd>
                    <dt>Email</dt><dd>{{ $record->primary_contact_email ?: '-' }}</dd>
                    <dt>Phone</dt><dd>{{ $record->primary_contact_phone ?: '-' }}</dd>
                </dl>
            </div>

            <div class="card">
                <h2>Update Status</h2>
                <form method="POST" action="{{ route('clinical-regulatory.records.status', $record) }}">
                    @csrf
                    <label>Status</label>
                    <select name="status">
                        @foreach($statuses as $status => $label)
                            <option value="{{ $status }}" @selected($record->status === $status)>{{ $label }}</option>
                        @endforeach
                    </select>

                    <label>Priority</label>
                    <select name="priority">
                        @foreach($priorities as $priority => $label)
                            <option value="{{ $priority }}" @selected($record->priority === $priority)>{{ $label }}</option>
                        @endforeach
                    </select>

                    <label>Due Date</label>
                    <input name="due_date" type="date" value="{{ $record->due_date?->format('Y-m-d') }}">

                    <label>Current Blocker</label>
                    <textarea name="current_blocker" rows="3">{{ $record->current_blocker }}</textarea>

                    <label>Missing Documents</label>
                    <textarea name="missing_documents" rows="3">{{ $record->missing_documents }}</textarea>

                    <label>Next Step</label>
                    <input name="next_step" value="{{ $record->next_step }}">

                    <label>Notes</label>
                    <textarea name="notes" rows="4">{{ $record->notes }}</textarea>

                    <button>Update Record</button>
                </form>
            </div>
        </aside>
    </div>
</body>
</html>
