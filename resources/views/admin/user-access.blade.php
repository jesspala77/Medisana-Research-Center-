<!doctype html>
<html>
<head>
    <title>User Access Management</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 32px; background: #f6f7f9; color: #111827; }
        .card { background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px; margin-bottom: 16px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .modules { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 6px; margin-top: 8px; }
        table { width: 100%; border-collapse: collapse; background: white; border: 1px solid #e5e7eb; }
        th, td { border-bottom: 1px solid #e5e7eb; padding: 10px; text-align: left; vertical-align: top; }
        th { background: #f9fafb; font-size: 13px; color: #374151; }
        input[type="text"] { width: 100%; box-sizing: border-box; padding: 8px; border: 1px solid #d1d5db; border-radius: 6px; }
        .btn { display: inline-block; background: #111827; color: #fff; border: 0; border-radius: 8px; padding: 9px 12px; font-weight: 700; cursor: pointer; }
        .muted { color: #6b7280; font-size: 12px; }
        .ok { background: #ecfdf5; border: 1px solid #34d399; color: #065f46; border-radius: 8px; padding: 10px 12px; margin-bottom: 12px; }
        @media (max-width: 980px) {
            .grid, .modules { grid-template-columns: 1fr; }
            table, thead, tbody, th, td, tr { display: block; }
            th { display: none; }
            td { border-bottom: 1px solid #e5e7eb; }
        }
    </style>
    @include('shared.synergia-styles')
</head>
<body>
    @include('shared.synergia-nav')

    <div class="card">
        <h1>User Access Management</h1>
        <p>Assign module access by company user. Platform admins keep full access to all modules.</p>
        <p class="muted">Modules: {{ collect($modules)->pluck('short_title')->implode(', ') }}</p>
    </div>

    @if(session('success'))
        <div class="ok">{{ session('success') }}</div>
    @endif

    <table>
        <thead>
            <tr>
                <th>User</th>
                <th>Organization</th>
                <th>Platform Admin</th>
                <th>Module Access</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $managedUser)
                <tr>
                    <td>
                        <strong>{{ $managedUser->name }}</strong><br>
                        <span class="muted">{{ $managedUser->email }}</span>
                    </td>
                    <td colspan="4">
                        <form method="POST" action="{{ route('admin.user-access.update', $managedUser) }}">
                            @csrf
                            <div class="grid">
                                <div>
                                    <label>Organization Key</label>
                                    <input type="text" name="organization_key" value="{{ old('organization_key', $managedUser->organization_key) }}" placeholder="k-and-g-art-designs">
                                </div>
                                <div>
                                    <label>
                                        <input type="checkbox" name="is_platform_admin" value="1" @checked($managedUser->is_platform_admin)>
                                        Platform Admin (full access)
                                    </label>
                                </div>
                            </div>

                            <div class="modules">
                                @foreach($moduleKeys as $module)
                                    <label>
                                        <input
                                            type="checkbox"
                                            name="module_access[]"
                                            value="{{ $module }}"
                                            @checked(in_array($module, $managedUser->module_access ?? [], true))
                                        >
                                        {{ $modules[$module]['short_title'] ?? $module }}
                                        <span class="muted">({{ $module }})</span>
                                    </label>
                                @endforeach
                            </div>

                            <div style="margin-top: 10px;">
                                <button class="btn" type="submit">Save Access</button>
                            </div>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
