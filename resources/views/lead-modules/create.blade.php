<!doctype html>
<html>
<head>
    <title>{{ $module['create_button'] }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 32px; max-width: 980px; color: #111827; }
        .grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
        label { display: block; margin-top: 12px; font-weight: 700; }
        input, select, textarea { width: 100%; box-sizing: border-box; padding: 9px; margin-top: 4px; border: 1px solid #d1d5db; border-radius: 6px; }
        input[type="checkbox"] { width: auto; }
        .full { grid-column: 1 / -1; }
        .button, button { margin-top: 18px; background: #111827; color: white; padding: 10px 14px; border: 0; border-radius: 8px; text-decoration: none; cursor: pointer; }
        .error { color: #b91c1c; font-size: 13px; margin-top: 4px; }
        @media (max-width: 760px) { .grid { grid-template-columns: 1fr; } }
    </style>
    @include('shared.synergia-styles')
</head>
<body>
    @include('shared.synergia-nav')
    <p><a href="{{ route($module['route_name'].'.dashboard') }}">Back to dashboard</a></p>
    <h1>{{ $module['create_button'] }}</h1>
    <p>{{ $module['description'] }}</p>

    <form method="POST" action="{{ route($module['route_name'].'.leads.store') }}">
        @csrf
        <div class="grid">
            <div class="full">
                <label>Lead Title</label>
                <input name="title" value="{{ old('title') }}" required placeholder="Business, agency, patient, or opportunity name">
                @error('title')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div>
                <label>Company / Organization</label>
                <input name="company_name" value="{{ old('company_name') }}">
                @error('company_name')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div>
                <label>Lead Source</label>
                <input name="lead_source" value="{{ old('lead_source') }}" placeholder="Website, referral, campaign, outbound">
                @error('lead_source')<div class="error">{{ $message }}</div>@enderror
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
                <label>Email</label>
                <input name="email" type="email" value="{{ old('email') }}">
                @error('email')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div>
                <label>Estimated Value</label>
                <input name="estimated_value" type="number" step="0.01" value="{{ old('estimated_value') }}">
                @error('estimated_value')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div>
                <label>Priority</label>
                <select name="priority">
                    @foreach(['low', 'normal', 'high', 'urgent'] as $priority)
                        <option value="{{ $priority }}" @selected(old('priority', 'normal') === $priority)>{{ ucfirst($priority) }}</option>
                    @endforeach
                </select>
                @error('priority')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div>
                <label>Next Follow-up</label>
                <input name="next_follow_up_at" type="datetime-local" value="{{ old('next_follow_up_at') }}">
                @error('next_follow_up_at')<div class="error">{{ $message }}</div>@enderror
            </div>

            @foreach($module['profile_fields'] as $field)
                @php
                    $fieldName = 'profile['.$field['name'].']';
                    $oldKey = 'profile.'.$field['name'];
                    $isRequired = in_array($field['name'], $module['required_profile_fields'], true);
                    $fieldValue = old($oldKey);
                @endphp
                <div class="{{ $field['type'] === 'textarea' ? 'full' : '' }}">
                    <label>{{ $field['label'] }} @if($isRequired)<span class="error">*</span>@endif</label>
                    @if($field['type'] === 'textarea')
                        <textarea name="{{ $fieldName }}" rows="4">{{ $fieldValue }}</textarea>
                    @elseif($field['type'] === 'select')
                        <select name="{{ $fieldName }}">
                            @foreach($field['options'] as $option)
                                <option value="{{ $option }}" @selected($fieldValue === $option)>{{ $option ?: 'Select' }}</option>
                            @endforeach
                        </select>
                    @elseif($field['type'] === 'checkbox')
                        <input type="hidden" name="{{ $fieldName }}" value="0">
                        <input type="checkbox" name="{{ $fieldName }}" value="1" @checked((bool) $fieldValue)>
                    @else
                        <input name="{{ $fieldName }}" type="{{ $field['type'] }}" step="{{ $field['step'] ?? '' }}" value="{{ $fieldValue }}">
                    @endif
                    @error($oldKey)<div class="error">{{ $message }}</div>@enderror
                </div>
            @endforeach

            <div class="full">
                <label>Summary / Notes</label>
                <textarea name="summary" rows="5">{{ old('summary') }}</textarea>
                @error('summary')<div class="error">{{ $message }}</div>@enderror
            </div>
        </div>

        <button>Create Lead</button>
    </form>
</body>
</html>
