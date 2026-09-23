<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request as HttpRequest;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MicrosoftCalendarTest extends TestCase
{
    use RefreshDatabase;

    public function test_expired_microsoft_access_token_is_refreshed_before_listing_calendar_events(): void
    {
        config([
            'services.microsoft.client_id' => 'client-id',
            'services.microsoft.client_secret' => 'client-secret',
            'services.microsoft.tenant' => 'test-tenant',
            'services.microsoft.scopes' => ['openid', 'profile', 'email', 'offline_access', 'User.Read', 'Mail.Send', 'Calendars.ReadWrite'],
        ]);

        $user = User::factory()->create([
            'microsoft_email' => 'jess@example.com',
            'microsoft_access_token' => 'old-token',
            'microsoft_refresh_token' => 'refresh-token',
            'microsoft_token_expires_at' => now()->subMinute(),
        ]);

        Http::fake([
            'https://login.microsoftonline.com/test-tenant/oauth2/v2.0/token' => Http::response([
                'access_token' => 'new-token',
                'refresh_token' => 'new-refresh-token',
                'expires_in' => 3600,
            ]),
            'https://graph.microsoft.com/v1.0/me/calendarView*' => Http::response([
                'value' => [
                    [
                        'id' => 'event-1',
                        'subject' => 'Outlook Review',
                        'start' => ['dateTime' => '2026-08-14T10:00:00'],
                        'end' => ['dateTime' => '2026-08-14T10:30:00'],
                        'isAllDay' => false,
                    ],
                ],
            ]),
        ]);

        $this->actingAs($user)
            ->getJson('/calendar/events?start=2026-08-01&end=2026-08-31')
            ->assertOk()
            ->assertJsonPath('0.id', 'outlook-event-1')
            ->assertJsonPath('0.title', 'Outlook Review');

        $user->refresh();

        $this->assertSame('new-token', $user->microsoft_access_token);
        $this->assertSame('new-refresh-token', $user->microsoft_refresh_token);

        Http::assertSent(function (HttpRequest $request) {
            return $request->url() === 'https://login.microsoftonline.com/test-tenant/oauth2/v2.0/token'
                && $request['scope'] === 'openid profile email offline_access User.Read Mail.Send Calendars.ReadWrite';
        });

        Http::assertSent(function (HttpRequest $request) {
            return str_starts_with($request->url(), 'https://graph.microsoft.com/v1.0/me/calendarView')
                && $request->hasHeader('Authorization', 'Bearer new-token');
        });
    }

    public function test_calendar_event_can_sync_to_outlook_with_refreshed_token(): void
    {
        config([
            'services.microsoft.client_id' => 'client-id',
            'services.microsoft.client_secret' => 'client-secret',
            'services.microsoft.tenant' => 'test-tenant',
            'services.microsoft.scopes' => ['openid', 'profile', 'email', 'offline_access', 'User.Read', 'Mail.Send', 'Calendars.ReadWrite'],
        ]);

        $user = User::factory()->create([
            'microsoft_email' => 'jess@example.com',
            'microsoft_access_token' => 'old-token',
            'microsoft_refresh_token' => 'refresh-token',
            'microsoft_token_expires_at' => now()->subMinute(),
        ]);

        Http::fake([
            'https://login.microsoftonline.com/test-tenant/oauth2/v2.0/token' => Http::response([
                'access_token' => 'new-token',
                'refresh_token' => 'new-refresh-token',
                'expires_in' => 3600,
            ]),
            'https://graph.microsoft.com/v1.0/me/events' => Http::response([
                'id' => 'outlook-event-id',
            ], 201),
        ]);

        $this->actingAs($user)
            ->post('/calendar/events', [
                'title' => 'Coverage Review',
                'description' => 'Review surety coverage.',
                'starts_at' => '2026-08-14 09:00:00',
                'ends_at' => '2026-08-14 09:30:00',
                'location' => 'Teams',
                'event_type' => 'meeting',
                'sync_to_outlook' => '1',
            ])
            ->assertRedirect(route('calendar.index'))
            ->assertSessionHas('success', 'Calendar event created successfully.');

        $this->assertDatabaseHas('calendar_events', [
            'user_id' => $user->id,
            'title' => 'Coverage Review',
            'source' => 'synnexus_outlook',
            'outlook_event_id' => 'outlook-event-id',
        ]);

        Http::assertSent(function (HttpRequest $request) {
            $payload = $request->data();

            return $request->url() === 'https://graph.microsoft.com/v1.0/me/events'
                && $request->hasHeader('Authorization', 'Bearer new-token')
                && $payload['subject'] === 'Coverage Review'
                && $payload['location']['displayName'] === 'Teams';
        });
    }
}
