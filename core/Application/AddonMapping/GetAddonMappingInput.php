<?php

declare(strict_types=1);

namespace Core\Application\AddonMapping;

use Core\Application\BaseInput;

class GetAddonMappingInput extends BaseInput
{
    /** @var array */
    private $addonIds;

    /**
     * GetAddonMappingInput constructor.
     * @param string $addonIds
     * @throws \Core\Application\InputException
     */
    public function __construct(string $addonIds)
    {
        $this->addonIds = $this->parseArrayAsJson($addonIds, false);
        $this->validateArrayOfIntegers($this->addonIds);
    }

    /**
     * @return array
     * @codeCoverageIgnore
     */
    public function getAddonIds(): array
    {
        return $this->addonIds;
    }
}
