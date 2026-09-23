<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SynNexus Calendar</title>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

    <style>
        :root {
            --navy: #071426;
            --navy-soft: #10243d;
            --gold: #c79a3d;
            --gold-soft: #ead8ad;
            --blue: #2563eb;
            --surface: #ffffff;
            --muted: #64748b;
            --line: #dbe3ef;
            --background: #f4f7fb;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--background);
            color: var(--navy);
            font-family: Arial, Helvetica, sans-serif;
        }

        .shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 280px 1fr;
        }

        .sidebar {
            background: var(--navy);
            color: #fff;
            padding: 24px 20px;
        }

        .brand {
            border-bottom: 1px solid rgba(255, 255, 255, .14);
            padding-bottom: 18px;
            margin-bottom: 22px;
        }

        .brand-title {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: .5px;
        }

        .brand-subtitle {
            margin-top: 6px;
            color: var(--gold-soft);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1.4px;
        }

        .nav-link {
            display: block;
            color: #dbeafe;
            text-decoration: none;
            padding: 11px 12px;
            border-radius: 6px;
            font-size: 14px;
            margin-bottom: 6px;
        }

        .nav-link.active,
        .nav-link:hover {
            background: rgba(199, 154, 61, .16);
            color: #fff;
        }

        .content {
            padding: 24px;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 18px;
        }

        .page-title {
            margin: 0;
            font-size: 26px;
            line-height: 32px;
        }

        .page-kicker {
            margin-top: 4px;
            color: var(--muted);
            font-size: 14px;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            border: 1px solid var(--line);
            background: #fff;
            font-size: 13px;
            color: var(--navy-soft);
        }

        .dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #22c55e;
        }

        .grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 360px;
            gap: 18px;
            align-items: start;
        }

        .panel {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 8px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, .06);
        }

        .panel-header {
            padding: 16px 18px;
            border-bottom: 1px solid var(--line);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .panel-title {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
        }

        .panel-body {
            padding: 18px;
        }

        #calendar {
            min-height: 720px;
        }

        .fc {
            --fc-border-color: #dbe3ef;
            --fc-today-bg-color: #fff8e8;
            --fc-button-bg-color: #071426;
            --fc-button-border-color: #071426;
            --fc-button-hover-bg-color: #10243d;
            --fc-button-hover-border-color: #10243d;
            --fc-button-active-bg-color: #c79a3d;
            --fc-button-active-border-color: #c79a3d;
            font-size: 14px;
        }

        .fc .fc-toolbar-title {
            color: var(--navy);
            font-size: 20px;
        }

        .form-group {
            margin-bottom: 13px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: var(--navy-soft);
            font-size: 13px;
            font-weight: 700;
        }

        input,
        select,
        textarea {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 6px;
            padding: 10px 11px;
            font-size: 14px;
            color: var(--navy);
            background: #fff;
            outline: none;
        }

        textarea {
            min-height: 90px;
            resize: vertical;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(199, 154, 61, .14);
        }

        .checkbox-row {
            display: flex;
            align-items: center;
            gap: 9px;
            margin: 10px 0 16px;
            color: var(--navy-soft);
            font-size: 14px;
        }

        .checkbox-row input {
            width: 16px;
            height: 16px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            border: 0;
            border-radius: 6px;
            background: var(--navy);
            color: #fff;
            font-weight: 700;
            font-size: 14px;
            padding: 11px 14px;
            cursor: pointer;
        }

        .btn:hover {
            background: var(--navy-soft);
        }

        .alert {
            padding: 11px 13px;
            border-radius: 6px;
            margin-bottom: 14px;
            font-size: 14px;
        }

        .alert-success {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .legend {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-top: 14px;
            padding-top: 14px;
            border-top: 1px solid var(--line);
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 7px;
            color: var(--muted);
            font-size: 12px;
        }

        .legend-color {
            width: 12px;
            height: 12px;
            border-radius: 3px;
        }

        .details {
            margin-top: 14px;
            padding-top: 14px;
            border-top: 1px solid var(--line);
            color: var(--muted);
            font-size: 13px;
            line-height: 19px;
        }

        @media (max-width: 1100px) {
            .shell {
                grid-template-columns: 1fr;
            }

            .sidebar {
                display: none;
            }

            .grid {
                grid-template-columns: 1fr;
            }

            .content {
                padding: 16px;
            }
        }
    </style>
</head>
<body>
<div class="shell">
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-title">SynNexus</div>
            <div class="brand-subtitle">Enterprise Calendar</div>
        </div>

        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
        <a class="nav-link active" href="{{ route('calendar.index') }}">Calendar</a>
        <a class="nav-link" href="{{ route('command-queue.index') }}">Command Queue</a>
        <a class="nav-link" href="{{ route('bond-agency.dashboard') }}">Bail Bond Agency</a>
    </aside>

    <main class="content">
        <div class="topbar">
            <div>
                <h1 class="page-title">SynNexus Calendar</h1>
                <div class="page-kicker">Outlook calendar, SynNexus events, and command follow-ups in one view.</div>
            </div>

            <div class="status-pill">
                <span class="dot"></span>
                Microsoft Graph Connected
            </div>
        </div>

        <div class="grid">
            <section class="panel">
                <div class="panel-header">
                    <h2 class="panel-title">Calendar View</h2>
                </div>
                <div class="panel-body">
                    <div id="calendar"></div>
                </div>
            </section>

            <aside class="panel">
                <div class="panel-header">
                    <h2 class="panel-title">Create Event</h2>
                </div>

                <div class="panel-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-error">{{ session('error') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-error">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('calendar.events.store') }}">
                        @csrf

                        <div class="form-group">
                            <label for="title">Title</label>
                            <input id="title" name="title" type="text" value="{{ old('title') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="starts_at">Start</label>
                            <input id="starts_at" name="starts_at" type="datetime-local" value="{{ old('starts_at') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="ends_at">End</label>
                            <input id="ends_at" name="ends_at" type="datetime-local" value="{{ old('ends_at') }}">
                        </div>

                        <div class="form-group">
                            <label for="event_type">Event Type</label>
                            <select id="event_type" name="event_type">
                                <option value="meeting">Meeting</option>
                                <option value="task">Task</option>
                                <option value="follow_up">Follow Up</option>
                                <option value="deadline">Deadline</option>
                                <option value="agency_outreach">Agency Outreach</option>
                                <option value="funding_review">Funding Review</option>
                                <option value="clinical_visit">Clinical Visit</option>
                                <option value="construction_estimate">Construction Estimate</option>
                                <option value="personal">Personal</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="location">Location</label>
                            <input id="location" name="location" type="text" value="{{ old('location') }}">
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description">{{ old('description') }}</textarea>
                        </div>

                        <label class="checkbox-row">
                            <input type="checkbox" name="sync_to_outlook" value="1" checked>
                            Sync this event to Outlook
                        </label>

                        <button class="btn" type="submit">Create Calendar Event</button>
                    </form>

                    <div class="legend">
                        <div class="legend-item">
                            <span class="legend-color" style="background:#2563eb;"></span>
                            Outlook
                        </div>
                        <div class="legend-item">
                            <span class="legend-color" style="background:#0f172a;"></span>
                            SynNexus
                        </div>
                    </div>

                    <div id="eventDetails" class="details">
                        Select an event to view details.
                    </div>
                </div>
            </aside>
        </div>
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');
        const detailsEl = document.getElementById('eventDetails');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 'auto',
            nowIndicator: true,
            selectable: true,
            navLinks: true,
            eventDisplay: 'block',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },
            events: '{{ route('calendar.events') }}',
            select: function (info) {
                const startsAt = document.getElementById('starts_at');
                const endsAt = document.getElementById('ends_at');

                startsAt.value = formatDateTimeLocal(info.start);

                if (info.end) {
                    endsAt.value = formatDateTimeLocal(info.end);
                }

                document.getElementById('title').focus();
            },
            eventClick: function (info) {
                const props = info.event.extendedProps || {};

                detailsEl.innerHTML = `
                    <strong>${escapeHtml(info.event.title)}</strong><br>
                    Source: ${escapeHtml(props.source || 'calendar')}<br>
                    Type: ${escapeHtml(props.event_type || 'outlook')}<br>
                    Status: ${escapeHtml(props.status || 'scheduled')}<br>
                    Location: ${escapeHtml(props.location || 'Not specified')}<br>
                    ${props.description ? '<br>' + escapeHtml(props.description) : ''}
                `;
            }
        });

        calendar.render();

        function formatDateTimeLocal(date) {
            const pad = number => String(number).padStart(2, '0');

            return [
                date.getFullYear(),
                pad(date.getMonth() + 1),
                pad(date.getDate())
            ].join('-') + 'T' + [
                pad(date.getHours()),
                pad(date.getMinutes())
            ].join(':');
        }

        function escapeHtml(value) {
            return String(value)
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        }
    });
</script>
</body>
</html>