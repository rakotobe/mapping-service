<?php

return [
    'store' => [
            'driver' => 'memcached',
            'server' => env('MEMCACHE_SERVER', 'catalog_delivery_memcached'),
            'port' => env('MEMCACHE_PORT', 11211),
        ],
];
