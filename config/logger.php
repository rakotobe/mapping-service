<?php

return [
    'folder' => storage_path(env('LOG_FOLDER', 'logs/pcaas/')),
    'service_name' => env('LOG_SERVICE_NAME', 'addon-mapping-service')
];