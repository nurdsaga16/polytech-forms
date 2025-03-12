    <?php

    return [
        'paths' => ['api/*'],
        'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],
        'allowed_origins' => ['*'],
        'allowed_headers' => ['*'],
        'exposed_headers' => [],
        'max_age' => 0,
        'supports_credentials' => false,
    ];
