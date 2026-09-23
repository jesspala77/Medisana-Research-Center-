<?php

namespace App\Services\Microsoft;

use App\Models\CalendarEvent;
use App\Models\User;
use App\Services\MicrosoftGraphTokenService;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class MicrosoftCalendarService
{
    public function __construct(private MicrosoftGraphTokenService $tokens) {}

    public function listEvents(User $user, $start, $end): array
    {
        $response = Http::withToken($this->tokens->accessToken($user))
            ->get('https://graph.microsoft.com/v1.0/me/calendarView', [
                'startDateTime' => $start->toIso8601String(),
                'endDateTime' => $end->toIso8601String(),
                '$orderby' => 'start/dateTime',
                '$top' => 100,
            ]);

        if ($response->failed()) {
            throw new RuntimeException($response->body());
        }

        return $response->json('value', []);
    }

    public function createEvent(User $user, CalendarEvent $event): array
    {
        $timeZone = config('app.timezone', 'America/New_York');

        $response = Http::withToken($this->tokens->accessToken($user))
            ->post('https://graph.microsoft.com/v1.0/me/events', [
                'subject' => $event->title,
                'body' => [
                    'contentType' => 'Text',
                    'content' => $event->description ?? '',
                ],
                'start' => [
                    'dateTime' => $event->starts_at->format('Y-m-d\TH:i:s'),
                    'timeZone' => $timeZone,
                ],
                'end' => [
                    'dateTime' => ($event->ends_at ?? $event->starts_at->copy()->addHour())->format('Y-m-d\TH:i:s'),
                    'timeZone' => $timeZone,
                ],
                'location' => [
                    'displayName' => $event->location ?? '',
                ],
            ]);

        if ($response->failed()) {
            throw new RuntimeException($response->body());
        }

        return $response->json();
    }
}
