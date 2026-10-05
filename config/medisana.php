<?php

return [
    'domain' => env('MEDISANA_DOMAIN', 'medisana-research.com'),

    // Leave blank until the corresponding official profile is live and verified.
    'social_links' => [
        'instagram' => env('MEDISANA_INSTAGRAM_URL', ''),
        'facebook' => env('MEDISANA_FACEBOOK_URL', ''),
        'linkedin' => env('MEDISANA_LINKEDIN_URL', ''),
    ],

    'portal_url' => env(
        'SYNNEXUS_PORTAL_URL',
        rtrim((string) env('APP_URL', 'http://localhost'), '/').'/login'
    ),
];
