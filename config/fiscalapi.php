<?php

return [
    'apiUrl' => env('FISCALAPI_URL', 'https://test.fiscalapi.com'),
    'apiKey' => env('FISCALAPI_KEY', ''),
    'tenant' => env('FISCALAPI_TENANT', ''),
    'debug' => env('FISCALAPI_DEBUG', false),
    'verifySsl' => env('FISCALAPI_VERIFY_SSL', true),
    'apiVersion' => env('FISCALAPI_API_VERSION', 'v4'),
    'timeZone' => env('FISCALAPI_TIMEZONE', 'America/Mexico_City')
];