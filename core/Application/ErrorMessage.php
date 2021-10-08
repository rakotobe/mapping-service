<?php

namespace Core\Application;

class ErrorMessage
{
    public const ADDON_MAPPING_NOT_FOUND_ERROR = 1000;
    public const BUNDLE_MAPPING_NOT_FOUND_ERROR = 1001;

    public const ERROR_MESSAGE = [
        self::ADDON_MAPPING_NOT_FOUND_ERROR => 'No addon mapping found',
        self::BUNDLE_MAPPING_NOT_FOUND_ERROR => 'No bundle mapping found'
    ];
}