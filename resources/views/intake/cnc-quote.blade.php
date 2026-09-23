<!doctype html>
<html>
<head>
    <title>K &amp; G Art Designs CNC Quote Intake</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; color: #111827; max-width: 980px; }
        .grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
        .full { grid-column: 1 / -1; }
        label { display: block; margin-top: 12px; font-weight: 700; }
        input, select, textarea { width: 100%; box-sizing: border-box; border: 1px solid #d1d5db; border-radius: 6px; padding: 9px; margin-top: 4px; }
        button { margin-top: 18px; background: #111827; color: #fff; border: 0; border-radius: 8px; padding: 10px 14px; cursor: pointer; }
        .error { color: #b91c1c; font-size: 13px; margin-top: 4px; }
        .ok { background: #ecfdf5; border: 1px solid #34d399; color: #065f46; border-radius: 8px; padding: 10px 12px; margin: 12px 0; }
        @media (max-width: 760px) { .grid { grid-template-columns: 1fr; } }
    </style>
    @include('shared.synergia-styles')
</head>
<body>
    @include('shared.synergia-nav')

    <h1>K &amp; G Art Designs CNC Quote Intake</h1>
    <p>Submit your RFQ details for K &amp; G Art Designs. SynNexus intake model processes your request first and creates the lead immediately for CNC review.</p>

    @if(session('success'))
        <div class="ok">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('intake.cnc.store') }}">
        @csrf
        <div class="grid">
            <div class="full">
                <label>Quote Title</label>
                <input name="title" value="{{ old('title') }}" required placeholder="Example: Aluminum manifold prototype - 50 units">
                @error('title')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div>
                <label>Company Name</label>
                <input name="company_name" value="{{ old('company_name') }}">
                @error('company_name')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div>
                <label>Contact Email</label>
                <input name="email" type="email" value="{{ old('email') }}">
                @error('email')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div>
                <label>First Name</label>
                <input name="first_name" value="{{ old('first_name') }}">
                @error('first_name')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div>
                <label>Last Name</label>
                <input name="last_name" value="{{ old('last_name') }}">
                @error('last_name')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div>
                <label>Phone</label>
                <input name="phone" value="{{ old('phone') }}">
                @error('phone')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div>
                <label>Priority</label>
                <select name="priority">
                    @foreach(['low', 'normal', 'high', 'urgent'] as $priority)
                        <option value="{{ $priority }}" @selected(old('priority', 'high') === $priority)>{{ ucfirst($priority) }}</option>
                    @endforeach
                </select>
                @error('priority')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div>
                <label>Part Name *</label>
                <input name="profile[part_name]" value="{{ old('profile.part_name') }}" required>
                @error('profile.part_name')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div>
                <label>Material *</label>
                <input name="profile[material]" value="{{ old('profile.material') }}" required>
                @error('profile.material')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div>
                <label>Quantity *</label>
                <input name="profile[quantity]" type="number" step="1" value="{{ old('profile.quantity') }}" required>
                @error('profile.quantity')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div>
                <label>Process Type *</label>
                <select name="profile[process_type]" required>
                    @foreach(['', 'CNC Milling', 'CNC Turning', 'Mill-Turn', 'Waterjet', 'Other'] as $option)
                        <option value="{{ $option }}" @selected(old('profile.process_type') === $option)>{{ $option ?: 'Select process' }}</option>
                    @endforeach
                </select>
                @error('profile.process_type')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div>
                <label>Target Unit Price</label>
                <input name="profile[target_unit_price]" type="number" step="0.01" value="{{ old('profile.target_unit_price') }}">
                @error('profile.target_unit_price')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div>
                <label>Due Date</label>
                <input name="profile[due_date]" type="date" value="{{ old('profile.due_date') }}">
                @error('profile.due_date')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div>
                <label>CAD File URL</label>
                <input name="profile[cad_file_url]" value="{{ old('profile.cad_file_url') }}" placeholder="https://...">
                @error('profile.cad_file_url')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div>
                <label>Shipping Postal Code</label>
                <input name="profile[shipping_postal_code]" value="{{ old('profile.shipping_postal_code') }}">
                @error('profile.shipping_postal_code')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="full">
                <label>Tolerance / Finish Notes</label>
                <textarea name="profile[tolerance_notes]" rows="4">{{ old('profile.tolerance_notes') }}</textarea>
                @error('profile.tolerance_notes')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="full">
                <label>Summary</label>
                <textarea name="summary" rows="4">{{ old('summary') }}</textarea>
                @error('summary')<div class="error">{{ $message }}</div>@enderror
            </div>
        </div>

        <button type="submit">Submit CNC RFQ</button>
    </form>
</body>
</html>