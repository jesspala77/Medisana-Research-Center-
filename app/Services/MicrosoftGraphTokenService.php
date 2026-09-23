<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class MicrosoftGraphTokenService
{
    public function accessToken(
        User $user,
        string $notConnectedMessage = 'Microsoft account is not connected.',
        string $reconnectMessage = 'Reconnect Microsoft and try again.'
    ): string {
        if (! $user->microsoft_access_token) {
            throw new RuntimeException($notConnectedMessage);
        }

        if (
            $user->microsoft_token_expires_at
            && $user->microsoft_token_expires_at->lte(now()->addMinutes(5))
        ) {
            return $this->refreshAccessToken($user, $reconnectMessage);
        }

        return $user->microsoft_access_token;
    }

    private function refreshAccessToken(User $user, string $reconnectMessage): string
    {
        if (! $user->microsoft_refresh_token) {
            throw new RuntimeException($reconnectMessage);
        }

        $tenant = config('services.microsoft.tenant') ?: 'common';

        $response = Http::asForm()->post("https://login.microsoftonline.com/{$tenant}/oauth2/v2.0/token", [
            'client_id' => config('services.microsoft.client_id'),
            'client_secret' => config('services.microsoft.client_secret'),
            'grant_type' => 'refresh_token',
            'refresh_token' => $user->microsoft_refresh_token,
            'scope' => implode(' ', config('services.microsoft.scopes', [])),
        ]);

        if (! $response->successful()) {
            throw new RuntimeException('Microsoft token refresh failed. '.$reconnectMessage);
        }

        $payload = $response->json();
        $accessToken = $payload['access_token'] ?? null;

        if (! $accessToken) {
            throw new RuntimeException('Microsoft did not return an access token. '.$reconnectMessage);
        }

        $user->forceFill([
            'microsoft_access_token' => $accessToken,
            'microsoft_refresh_token' => $payload['refresh_token'] ?? $user->microsoft_refresh_token,
            'microsoft_token_expires_at' => isset($payload['expires_in'])
                ? now()->addSeconds((int) $payload['expires_in'])
                : null,
        ])->save();

        return $accessToken;
    }
}
