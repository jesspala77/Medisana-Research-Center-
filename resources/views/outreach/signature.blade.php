<!doctype html>
<html>
<head>
    <title>Outreach Signature</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 32px; background: #f6f7f9; color: #111827; }
        main { max-width: 980px; }
        .card { background: white; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 18px; padding: 18px; }
        textarea { border: 1px solid #d1d5db; border-radius: 8px; box-sizing: border-box; font-family: ui-monospace, SFMono-Regular, Consolas, monospace; min-height: 360px; padding: 12px; width: 100%; }
        .button { background: #111827; border-radius: 8px; color: white; display: inline-flex; padding: 10px 14px; text-decoration: none; }
        .muted { color: #6b7280; }
    </style>
    @include('shared.synergia-styles')
</head>
<body>
    @include('shared.synergia-nav')

    <main>
        <p><a href="{{ route('bond-agency.outreach.index') }}">Outreach Queue</a></p>
        <h1>Outreach Signature</h1>

        <div class="card">
            {!! $signatureHtml !!}
        </div>

        <div class="card">
            <p class="muted">This signature is ready to copy into your email client.</p>
            <textarea readonly>{{ $signatureHtml }}</textarea>
        </div>
    </main>
</body>
</html>
