<?php

declare(strict_types=1);

namespace Core\Application;

class BaseInput
{
    /**
     * @param string $encoded
     * @param bool $isEmptyArrayAllowed
     * @return array
     * @throws InputException
     */
    protected function parseArrayAsJson(string $encoded, bool $isEmptyArrayAllowed): array
    {
        $array = @json_decode($encoded, true);
        if (!is_array($array)) {
            throw new InputException('Invalid JSON passed. It should be an array encoded as JSON');
        }
        if (empty($array) && !$isEmptyArrayAllowed) {
            throw new InputException('Empty array is not allowed');
        }
        return $array;
    }

    /**
     * @param array $addonIds
     * @throws InputException
     * @return bool
     */
    protected function validateArrayOfIntegers(array $addonIds): bool
    {
        foreach ($addonIds as $oneAddonId) {
            if (!is_int($oneAddonId)) {
                throw new InputException('Passed AddonIds must be integers');
            }
        }
        return true;
    }

    /**
     * @param array $addonIds
     * @throws InputException
     * @return bool
     */
    protected function validateArrayOfStrings(array $addonIds): bool
    {
        foreach ($addonIds as $oneAddonId) {
            if (!is_string($oneAddonId)) {
                throw new InputException('Passed AddonIds must be strings');
            }
        }
        return true;
    }
}
