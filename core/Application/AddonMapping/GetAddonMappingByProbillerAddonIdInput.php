<?php

declare(strict_types=1);

namespace Core\Application\AddonMapping;

use Core\Application\BaseInput;

class GetAddonMappingByProbillerAddonIdInput extends BaseInput
{
    /** @var array */
    private $probillerAddonIds;

    /**
     * GetAddonMappingByProbillerAddonIdInput constructor.
     * @param string $probillerAddonIds
     * @throws \Core\Application\InputException
     */
    public function __construct(string $probillerAddonIds)
    {
        $this->probillerAddonIds = $this->parseArrayAsJson($probillerAddonIds, false);
        $this->validateArrayOfStrings($this->probillerAddonIds);
    }

    /**
     * @return array
     * @codeCoverageIgnore
     */
    public function getProbillerAddonIds(): array
    {
        return $this->probillerAddonIds;
    }
}
