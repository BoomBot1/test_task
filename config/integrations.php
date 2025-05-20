<?php

return [
    'reestr_api' => [
        'is_fake' => env('INTEGRATIONS_REESTR_API_IS_FAKE', true),
        'token' => env('INTEGRATIONS_REESTR_API_TOKEN'),
        'base_url' => 'https://reestr-api.ru',
    ],
    'dadata' => [
        'is_fake' => env('DADATA_IS_FAKE', true),
        'token' => env('DADATA_TOKEN'),
        'secret' => env('DADATA_SECRET'),
    ],
];
