<!doctype html>
<html>
<head>
    <title>Bond Agency Outreach</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 32px; background: #f6f7f9; color: #111827; }
        main { max-width: 1280px; }
        .toolbar { display: flex; justify-content: space-between; align-items: flex-end; gap: 16px; margin-bottom: 18px; }
        .summary { display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 10px; margin: 18px 0; }
        .metric { background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; }
        .metric span { display: block; color: #6b7280; font-size: 12px; text-transform: uppercase; }
        .metric strong { display: block; font-size: 24px; margin-top: 4px; }
        .production { background: #ecfdf5; border: 1px solid #10b981; border-radius: 8px; color: #064e3b; display: grid; gap: 10px; grid-template-columns: repeat(4, minmax(0, 1fr)); margin-bottom: 16px; padding: 14px; }
        .production div { background: rgba(255,255,255,.6); border-radius: 8px; padding: 10px; }
        .production span { display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; }
        .production strong { display: block; font-size: 20px; margin-top: 4px; }
        .tabs { display: flex; flex-wrap: wrap; gap: 8px; margin: 18px 0; }
        .tabs a, .button, button { background: white; border: 1px solid #d1d5db; border-radius: 8px; color: #111827; cursor: pointer; display: inline-flex; padding: 9px 12px; text-decoration: none; }
        .tabs a.active, .button.primary, button.primary { background: #111827; border-color: #111827; color: white; }
        button:disabled { cursor: not-allowed; opacity: .45; }
        .message { background: white; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 14px; padding: 16px; }
        .message-header { display: grid; grid-template-columns: 1fr auto; gap: 16px; }
        .muted { color: #6b7280; }
        .meta { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 8px; }
        .status { background: #eef2ff; border-radius: 999px; color: #3730a3; padding: 3px 9px; text-transform: capitalize; }
        .editor { display: grid; gap: 10px; margin-top: 12px; }
        .fields { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; }
        label { color: #374151; display: grid; font-size: 12px; font-weight: 800; gap: 5px; text-transform: uppercase; }
        input, textarea { box-sizing: border-box; border: 1px solid #d1d5db; border-radius: 8px; color: #111827; font: inherit; padding: 10px 12px; width: 100%; }
        textarea { font-family: ui-monospace, SFMono-Regular, Consolas, monospace; min-height: 220px; }
        input:disabled, textarea:disabled { background: #f3f4f6; color: #6b7280; }
        .actions { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 12px; }
        .bulk-send { align-items: center; display: inline-flex; gap: 8px; }
        .bulk-send input { max-width: 74px; padding: 8px; }
        form { display: inline; }
        .empty { background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 22px; }
        .notice { background: #fffbeb; border: 1px solid #f59e0b; border-radius: 8px; color: #92400e; margin-bottom: 16px; padding: 12px; }
        .success { color: #047857; }
        .error { color: #b91c1c; }
        nav[role="navigation"] { margin-top: 18px; }
        @media (max-width: 950px) { .summary, .production, .fields { grid-template-columns: repeat(2, minmax(130px, 1fr)); } .message-header { grid-template-columns: 1fr; } }
        @media (max-width: 620px) { .summary, .production, .fields { grid-template-columns: 1fr; } }
    </style>
    @include('shared.synergia-styles')
</head>
<body>
    @include('shared.synergia-nav')

    <main>
        <div class="toolbar">
            <div>
                <p class="muted">Bond Agency</p>
                <h1>Outreach Queue</h1>
            </div>
            <div class="actions">
                <a class="button" href="{{ route('bond-agency.outreach.signature') }}">Signature</a>
                <a class="button primary" href="{{ route('bond-agency.outreach.export', ['status' => $status]) }}">Download CSV</a>
                <form method="POST" action="{{ route('bond-agency.outreach.send-approved') }}" onsubmit="return confirm('Send approved outreach emails through Microsoft?');">
                    @csrf
                    <span class="bulk-send">
                        <input
                            type="number"
                            name="limit"
                            value="{{ min(max($summary['ready_to_send'], 1), 10) }}"
                            min="1"
                            max="25"
                            aria-label="Approved email send limit"
                        >
                        <button
                            class="primary"
                            @if(! $microsoftConnected || $summary['ready_to_send'] === 0) disabled @endif
                        >
                            Send Approved
                        </button>
                    </span>
                </form>
            </div>
        </div>

        @if(session('success'))
            <p class="success">{{ session('success') }}</p>
        @endif

        @if(session('error'))
            <p class="error">{{ session('error') }}</p>
        @endif

        @unless($microsoftConnected)
            <div class="notice">
                Microsoft is not connected for this user. Connect it before sending outreach emails.
                <a href="{{ route('microsoft.redirect') }}">Connect Microsoft</a>
            </div>
        @else
            <div class="notice">
                Emails will send through Microsoft as {{ $senderEmail }}. Only approved, complete drafts can be sent.
            </div>
        @endif

        <section class="production" aria-label="Email Production">
            <div>
                <span>Email Production</span>
                <strong>{{ $microsoftConnected ? 'Connected' : 'Connect Microsoft' }}</strong>
            </div>
            <div>
                <span>Ready To Send</span>
                <strong>{{ $summary['ready_to_send'] }}</strong>
            </div>
            <div>
                <span>Blocked Approved</span>
                <strong>{{ $summary['blocked_approved'] }}</strong>
            </div>
            <div>
                <span>Sent Today</span>
                <strong>{{ $summary['sent_today'] }}</strong>
            </div>
        </section>

        <section class="summary">
            @foreach([
                'all' => 'All',
                'draft' => 'Draft',
                'approved' => 'Approved',
                'ready_to_send' => 'Ready',
                'blocked_approved' => 'Blocked',
                'sent' => 'Sent',
                'sent_today' => 'Sent Today',
                'replied' => 'Replied',
                'opted_out' => 'Opted Out',
                'missing_email' => 'No Email',
            ] as $key => $label)
                <div class="metric">
                    <span>{{ $label }}</span>
                    <strong>{{ $summary[$key] }}</strong>
                </div>
            @endforeach
        </section>

        <nav class="tabs" aria-label="Outreach status">
            @foreach([
                'draft' => 'Drafts',
                'approved' => 'Approved',
                'sent' => 'Sent',
                'replied' => 'Replied',
                'opted_out' => 'Opted Out',
                'missing_email' => 'No Email',
                'all' => 'All',
            ] as $key => $label)
                <a href="{{ route('bond-agency.outreach.index', ['status' => $key]) }}" @class(['active' => $status === $key])>{{ $label }}</a>
            @endforeach
        </nav>

        @forelse($messages as $message)
            @php
                $lead = $message->lead;
                $isLocked = in_array($message->status, ['sent', 'replied', 'opted_out'], true);
                $mailto = $message->recipient_email
                    ? 'mailto:'.$message->recipient_email.'?'.http_build_query([
                        'subject' => $message->subject,
                        'body' => $message->message_body,
                    ])
                    : null;
            @endphp

            <article class="message">
                <div class="message-header">
                    <div>
                        <h2>{{ $message->agency_name ?: 'Unnamed outreach' }}</h2>
                        <div class="meta">
                            <span class="status">{{ str_replace('_', ' ', $message->status) }}</span>
                            <span>{{ $message->recipient_name ?: 'No recipient name' }}</span>
                            <span>{{ $message->recipient_email ?: 'No email' }}</span>
                            <span>{{ $lead?->contact?->phone ?: 'No phone' }}</span>
                            <span>{{ $lead?->company?->city ?: ($lead?->metadata['city'] ?? '-') }}, {{ $lead?->company?->state ?: ($lead?->metadata['state'] ?? '-') }}</span>
                        </div>
                    </div>
                    <div>
                        @if($lead)
                            <a class="button" href="{{ route('bond-agency.leads.show', $lead) }}">Open Lead</a>
                        @endif
                    </div>
                </div>

                <form class="editor" method="POST" action="{{ route('bond-agency.outreach.update', $message) }}">
                    @csrf

                    <div class="fields">
                        <label>
                            Recipient Name
                            <input
                                name="recipient_name"
                                value="{{ old('recipient_name', $message->recipient_name) }}"
                                @if($isLocked) disabled @endif
                            >
                        </label>

                        <label>
                            Recipient Email
                            <input
                                name="recipient_email"
                                type="email"
                                value="{{ old('recipient_email', $message->recipient_email) }}"
                                @if($isLocked) disabled @endif
                            >
                        </label>
                    </div>

                    <label>
                        Subject
                        <input
                            name="subject"
                            value="{{ old('subject', $message->subject) }}"
                            required
                            @if($isLocked) disabled @endif
                        >
                    </label>

                    <label>
                        Message
                        <textarea
                            name="message_body"
                            required
                            @if($isLocked) disabled @endif
                        >{{ old('message_body', $message->message_body) }}</textarea>
                    </label>

                    @unless($isLocked)
                        <div>
                            <button>Save Draft</button>
                        </div>
                    @endunless
                </form>

                <div class="actions">
                    @if($mailto)
                        <a class="button primary" href="{{ $mailto }}">Open Email</a>
                    @endif

                    @if($message->status === 'draft')
                        <form method="POST" action="{{ route('bond-agency.outreach.approve', $message) }}">
                            @csrf
                            <button>Approve</button>
                        </form>
                    @endif

                    @if($message->status === 'approved' && $message->recipient_email)
                        <form method="POST" action="{{ route('bond-agency.outreach.send', $message) }}" onsubmit="return confirm('Send this outreach email through Microsoft?');">
                            @csrf
                            <button class="primary">Send Email</button>
                        </form>
                    @endif

                    @if(! in_array($message->status, ['sent', 'replied', 'opted_out'], true))
                        <form method="POST" action="{{ route('bond-agency.outreach.sent', $message) }}">
                            @csrf
                            <button>Mark Sent</button>
                        </form>
                    @endif

                    @if($message->status !== 'replied')
                        <form method="POST" action="{{ route('bond-agency.outreach.replied', $message) }}">
                            @csrf
                            <button>Mark Replied</button>
                        </form>
                    @endif

                    @if($message->status !== 'opted_out')
                        <form method="POST" action="{{ route('bond-agency.outreach.opt-out', $message) }}">
                            @csrf
                            <button>Opt Out</button>
                        </form>
                    @endif
                </div>
            </article>
        @empty
            <div class="empty">No outreach messages in this status.</div>
        @endforelse

        {{ $messages->links() }}
    </main>
</body>
</html>
