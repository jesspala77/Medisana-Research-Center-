<?php

return [
    'domain' => env('MEDISANA_DOMAIN', 'medisana-research.com'),

    'portal_url' => env(
        'SYNNEXUS_PORTAL_URL',
        rtrim((string) env('APP_URL', 'http://localhost'), '/').'/login'
    ),
];
