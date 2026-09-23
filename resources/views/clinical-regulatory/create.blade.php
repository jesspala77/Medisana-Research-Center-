<!doctype html>
<html>
<head>
    <title>Create Regulatory Record</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 32px; max-width: 1080px; color: #111827; }
        .grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
        label { display: block; margin-top: 12px; font-weight: 700; }
        input, select, textarea { width: 100%; box-sizing: border-box; padding: 9px; margin-top: 4px; border: 1px solid #d1d5db; border-radius: 6px; }
        .full { grid-column: 1 / -1; }
        .button, button { margin-top: 18px; background: #111827; color: white; padding: 10px 14px; border: 0; border-radius: 8px; text-decoration: none; cursor: pointer; }
        .error { color: #b91c1c; font-size: 13px; margin-top: 4px; }
        @media (max-width: 760px) { .grid { grid-template-columns: 1fr; } }
    </style>
    @include('shared.synergia-styles')
</head>
<body>
    @include('shared.synergia-nav')
    <p><a href="{{ route('clinical-regulatory.dashboard') }}">Back to regulatory dashboard</a></p>
    <h1>Create Regulatory Record</h1>
    <p>Use this record for regulatory service work only. Recruitment candidates stay in the clinical recruitment dashboard.</p>

    <form method="POST" action="{{ route('clinical-regulatory.records.store') }}">
        @csrf
        <div class="grid">
            <div class="full">
                <label>Request Title <span class="error">*</span></label>
                <input name="request_title" value="{{ old('request_title', $prefill['request_title'] ?? '') }}" required>
                @error('request_title')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div>
                <label>Client Name <span class="error">*</span></label>
                <input name="client_name" value="{{ old('client_name') }}" required>
                @error('client_name')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div>
                <label>Site Name</label>
                <input name="site_name" value="{{ old('site_name') }}">
                @error('site_name')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div>
                <label>Service Category <span class="error">*</span></label>
                <select name="service_category" required>
                    <option value="">Select</option>
                    @foreach($services as $key => $service)
                        <option value="{{ $key }}" @selected(old('service_category', $prefill['service_category'] ?? '') === $key)>{{ $service['title'] }}</option>
                    @endforeach
                </select>
                @error('service_category')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div>
                <label>Sponsor / CRO</label>
                <input name="sponsor" value="{{ old('sponsor') }}">
                @error('sponsor')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div>
                <label>Protocol / Study</label>
                <input name="protocol" value="{{ old('protocol') }}">
                @error('protocol')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div>
                <label>Regulatory Owner</label>
                <input name="regulatory_owner" value="{{ old('regulatory_owner') }}">
                @error('regulatory_owner')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div>
                <label>Primary Contact Name</label>
                <input name="primary_contact_name" value="{{ old('primary_contact_name') }}">
                @error('primary_contact_name')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div>
                <label>Primary Contact Email</label>
                <input name="primary_contact_email" type="email" value="{{ old('primary_contact_email') }}">
                @error('primary_contact_email')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div>
                <label>Primary Contact Phone</label>
                <input name="primary_contact_phone" value="{{ old('primary_contact_phone') }}">
                @error('primary_contact_phone')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div>
                <label>Status <span class="error">*</span></label>
                <select name="status" required>
                    @foreach($statuses as $status => $label)
                        <option value="{{ $status }}" @selected(old('status', $prefill['status'] ?? 'not_started') === $status)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('status')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div>
                <label>Priority <span class="error">*</span></label>
                <select name="priority" required>
                    @foreach($priorities as $priority => $label)
                        <option value="{{ $priority }}" @selected(old('priority', $prefill['priority'] ?? 'normal') === $priority)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('priority')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div>
                <label>Due Date</label>
                <input name="due_date" type="date" value="{{ old('due_date') }}">
                @error('due_date')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div>
                <label>Next Step</label>
                <input name="next_step" value="{{ old('next_step') }}">
                @error('next_step')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="full">
                <label>Documents Available</label>
                <textarea name="documents_available" rows="3">{{ old('documents_available') }}</textarea>
                @error('documents_available')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="full">
                <label>Missing Documents</label>
                <textarea name="missing_documents" rows="3">{{ old('missing_documents') }}</textarea>
                @error('missing_documents')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="full">
                <label>Current Blocker</label>
                <textarea name="current_blocker" rows="3">{{ old('current_blocker') }}</textarea>
                @error('current_blocker')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="full">
                <label>Approval Path</label>
                <textarea name="approval_path" rows="3">{{ old('approval_path') }}</textarea>
                @error('approval_path')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="full">
                <label>Notes</label>
                <textarea name="notes" rows="5">{{ old('notes', $prefill['notes'] ?? '') }}</textarea>
                @error('notes')<div class="error">{{ $message }}</div>@enderror
            </div>
        </div>

        <button>Create Regulatory Record</button>
    </form>
</body>
</html>
