<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Variza API Token
    |--------------------------------------------------------------------------
    |
    | Your Variza API token for authenticating requests. Get your token from:
    | https://variza.ir/panel/profile
    |
    */

    'api_token' => env('VARIZA_API_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Webhook Secret
    |--------------------------------------------------------------------------
    |
    | Your webhook secret for verifying webhook signatures. This is used to
    | ensure webhooks are genuinely from Variza and not forged.
    |
    */

    'webhook_secret' => env('VARIZA_WEBHOOK_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | API Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for the Variza API. You should not need to change this
    | unless you're testing against a local or staging environment.
    |
    */

    'base_url' => env('VARIZA_BASE_URL', 'https://variza.ir/api/v1'),

    /*
    |--------------------------------------------------------------------------
    | Webhook Path
    |--------------------------------------------------------------------------
    |
    | The URI path where Variza webhooks will be received. The full URL will
    | be: https://yourdomain.com/{webhook_path}
    |
    */

    'webhook_path' => env('VARIZA_WEBHOOK_PATH', 'variza/webhook'),

    /*
    |--------------------------------------------------------------------------
    | HTTP Timeout
    |--------------------------------------------------------------------------
    |
    | The maximum number of seconds to wait for API responses.
    |
    */

    'timeout' => env('VARIZA_TIMEOUT', 30),

];
