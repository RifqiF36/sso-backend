<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'v2/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['http://localhost:8000', 'http://localhost:8001', 'https://simantic.online', 'https://api.siprima.digitaltech.my.id', 'https://simantic.online',  'https://api.simantic.online', 'https://api-sindra.okkyprojects.com', 'http://127.0.0.1:9000', 'http://127.0.0.1:8000', 'http://127.0.0.1:8001', 'http://siprima.test', 'http://sindra.test', 'http://simantic.test',  'https://bispro.digitaltech.my.id', 'https://sindra.online', 'https://api.sindra.online'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
