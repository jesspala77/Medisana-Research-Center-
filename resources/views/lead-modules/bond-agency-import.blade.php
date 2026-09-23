<!doctype html>
<html>
<head>
    <title>Import Bail Bond Agencies</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 32px; background: #f6f7f9; color: #111827; }
        .import-shell { max-width: 980px; margin: 0 auto; }
        .import-panel { padding: 24px; }
        .import-panel form { margin-top: 18px; }
        .notice { border-radius: 8px; margin: 0 0 16px; padding: 12px 14px; }
        .notice.success { background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; }
        .notice.error { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
        .help-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; margin-top: 18px; }
        .help-card { padding: 18px; }
        .help-card p { margin-bottom: 0; }
        label { display: block; margin-top: 12px; }
        input { width: 100%; margin-top: 6px; }
        button { margin-top: 18px; }
        @media (max-width: 760px) { .help-grid { grid-template-columns: 1fr; } }
    </style>
    @include('shared.synergia-styles')
</head>
<body>
    @include('shared.synergia-nav')

    <main class="import-shell">
        <section class="topbar">
            <div>
                <p class="label">Bond agency data intake</p>
                <h1>Import Bail Bond Agencies</h1>
                <p>Upload a Synergia-formatted CSV or the Arizona DIFI / NAIC SBS report and SynNexus will create agency leads, contacts, and outreach drafts.</p>
            </div>
            <div class="nav">
                <a href="{{ route('bond-agency.dashboard') }}">Dashboard</a>
                <a class="secondary" href="{{ route('bond-agency.outreach.index') }}">Outreach</a>
            </div>
        </section>

        @if(session('success'))
            <p class="notice success">{{ session('success') }}</p>
        @endif

        @if($errors->any())
            <div class="notice error">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <section class="card import-panel">
            <h2>Upload Source File</h2>
            <form method="POST" action="{{ route('bond-agency.import.store') }}" enctype="multipart/form-data">
                @csrf

                <label>CSV File</label>
                <input type="file" name="csv_file" accept=".csv,text/csv" required>

                <button type="submit">Import Agencies</button>
            </form>
        </section>

        <section class="help-grid">
            <article class="card help-card">
                <h2>Synergia CSV</h2>
                <p>Use this when you already have a cleaned prospect list.</p>
                <pre>agency_name,contact_name,email,phone,address,city,state,zip,county,website,current_surety,coverage_gap</pre>
            </article>

            <article class="card help-card">
                <h2>Arizona DIFI / SBS Report</h2>
                <p>The importer maps common SBS columns including license number, NPN, business entity, license status, line of authority, business address, phone, email, resident status, and domicile state.</p>
            </article>
        </section>
    </main>
</body>
</html>
