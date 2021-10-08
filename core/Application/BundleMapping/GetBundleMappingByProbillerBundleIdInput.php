<?php

declare(strict_types=1);

namespace Core\Application\BundleMapping;

use Core\Application\BaseInput;

class GetBundleMappingByProbillerBundleIdInput extends BaseInput
{
    /** @var array */
    private $probillerBundleIds;

    /**
     * GetBundleMappingByProbillerBundleIdInput constructor.
     * @param string $probillerBundleIds
     * @throws \Core\Application\InputException
     */
    public function __construct(string $probillerBundleIds)
    {
        $this->probillerBundleIds = $this->parseArrayAsJson($probillerBundleIds, false);
        $this->validateArrayOfStrings($this->probillerBundleIds);
    }

    /**
     * @return array
     * @codeCoverageIgnore
     */
    public function getProbillerBundleIds(): array
    {
        return $this->probillerBundleIds;
    }
}
