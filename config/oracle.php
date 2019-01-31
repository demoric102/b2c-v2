<?php

return [
    
    'oracle' => [
        'driver'         => 'oracle',
        'host'           => env('ORACLE_HOST', '172.16.2.12'),
        'port'           => env('ORACLE_PORT', '1521'),
        'database'       => env('ORACLE_DATABASE', 'bulkuat'),
        'service_name' => env('SERVICE_NAME','bulkuat'),
        'username'       => env('ORACLE_USERNAME', 'sbinternal'),
        'password'       => env('ORACLE_PASSWORD', 'SBINTERNAL'),
        'charset'        => env('ORACLE_CHARSET', 'AL32UTF8'),
        'prefix'         => env('ORACLE_PREFIX', ''),
        'prefix_schema'  => env('ORACLE_SCHEMA_PREFIX', ''),
        'server_version' => env('ORACLE_SERVER_VERSION', '12c'),
    ],
];
