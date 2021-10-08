<?php

return [
    'url' => env('EVENTS_STORE_URL', 'http://events_store_php/'),
    'api_version' => env('EVENTS_STORE_API_VERSION', 1),
    'topic_name' => env('EVENTS_STORE_TOPIC_NAME', 'dev-test-topic'),
];
