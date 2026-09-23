<?php

namespace Tests\Feature;

use Tests\TestCase;

class MicrosoftAuthTest extends TestCase
{
    public function test_microsoft_redirect_uses_oauth_scopes(): void
    {
        config([
            'services.microsoft.client_id' => 'test-client-id',
            'services.microsoft.client_secret' => 'test-client-secret',
            'services.microsoft.redirect' => 'http://127.0.0.1:8000/auth/microsoft/callback',
            'services.microsoft.tenant' => 'test-tenant',
        ]);

        $response = $this->get('/auth/microsoft');

        $response->assertRedirect();

        $location = $response->headers->get('Location');
        $this->assertStringStartsWith(
            'https://login.microsoftonline.com/test-tenant/oauth2/v2.0/authorize?',
            $location
        );

        parse_str(parse_url($location, PHP_URL_QUERY), $query);

        $this->assertSame('test-client-id', $query['client_id']);
        $this->assertSame(
            'http://127.0.0.1:8000/auth/microsoft/callback',
            $query['redirect_uri']
        );
        $this->assertSame('code', $query['response_type']);
        $this->assertNotEmpty($query['state']);

        $scopes = explode(' ', $query['scope']);

        foreach (['openid', 'profile', 'email', 'offline_access', 'User.Read', 'Mail.Send', 'Calendars.ReadWrite'] as $scope) {
            $this->assertContains($scope, $scopes);
        }

        foreach (range(0, 5) as $numericParameter) {
            $this->assertArrayNotHasKey($numericParameter, $query);
        }
    }
}
