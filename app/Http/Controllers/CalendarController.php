<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Services\Microsoft\MicrosoftCalendarService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function index(): View
    {
        return view('calendar.index');
    }

    public function events(Request $request, MicrosoftCalendarService $microsoftCalendar): JsonResponse
    {
        $user = $request->user();

        $start = now()->startOfMonth();
        $end = now()->endOfMonth();

        if ($request->filled('start')) {
            $start = Carbon::parse($request->query('start'));
        }

        if ($request->filled('end')) {
            $end = Carbon::parse($request->query('end'));
        }

        $localEvents = CalendarEvent::query()
            ->where('user_id', $user->id)
            ->whereBetween('starts_at', [$start, $end])
            ->get()
            ->toBase()
            ->map(function (CalendarEvent $event) {
                return [
                    'id' => 'local-'.$event->id,
                    'title' => $event->title,
                    'start' => $event->starts_at?->toIso8601String(),
                    'end' => $event->ends_at?->toIso8601String(),
                    'allDay' => $event->all_day,
                    'backgroundColor' => '#0f172a',
                    'borderColor' => '#c79a3d',
                    'extendedProps' => [
                        'source' => 'synnexus',
                        'event_type' => $event->event_type,
                        'status' => $event->status,
                        'description' => $event->description,
                        'location' => $event->location,
                    ],
                ];
            });

        $outlookEvents = collect();

        try {
            $outlookEvents = collect($microsoftCalendar->listEvents($user, $start, $end))
                ->map(function (array $event) {
                    return [
                        'id' => 'outlook-'.$event['id'],
                        'title' => $event['subject'] ?? 'Outlook Event',
                        'start' => $event['start']['dateTime'] ?? null,
                        'end' => $event['end']['dateTime'] ?? null,
                        'allDay' => $event['isAllDay'] ?? false,
                        'backgroundColor' => '#2563eb',
                        'borderColor' => '#2563eb',
                        'extendedProps' => [
                            'source' => 'outlook',
                            'description' => strip_tags($event['bodyPreview'] ?? ''),
                            'location' => $event['location']['displayName'] ?? '',
                        ],
                    ];
                });
        } catch (\Throwable $exception) {
            report($exception);
        }

        return response()->json(
            $localEvents
                ->merge($outlookEvents)
                ->values()
        );
    }

    public function store(Request $request, MicrosoftCalendarService $microsoftCalendar): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'location' => ['nullable', 'string', 'max:255'],
            'event_type' => ['nullable', 'string', 'max:100'],
            'sync_to_outlook' => ['nullable', 'boolean'],
        ]);

        $event = CalendarEvent::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'starts_at' => $validated['starts_at'],
            'ends_at' => $validated['ends_at'] ?? null,
            'location' => $validated['location'] ?? null,
            'event_type' => $validated['event_type'] ?? 'meeting',
            'status' => 'scheduled',
            'source' => 'synnexus',
            'metadata' => [
                'created_from' => 'calendar_form',
            ],
        ]);

        if ($request->boolean('sync_to_outlook')) {
            try {
                $outlookEvent = $microsoftCalendar->createEvent($request->user(), $event);

                $event->update([
                    'outlook_event_id' => $outlookEvent['id'] ?? null,
                    'source' => 'synnexus_outlook',
                ]);
            } catch (\Throwable $exception) {
                report($exception);

                return redirect()
                    ->route('calendar.index')
                    ->with('error', 'Event was saved in SynNexus, but Outlook sync failed.');
            }
        }

        return redirect()
            ->route('calendar.index')
            ->with('success', 'Calendar event created successfully.');
    }
}
