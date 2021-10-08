<?php

declare(strict_types=1);

namespace Core\Application\BundleMapping;

use Core\Application\BaseInput;

class GetBundleMappingInput extends BaseInput
{
    /** @var array */
    private $bundleIds;

    /**
     * GetBundleMappingInput constructor.
     * @param string $bundleIds
     * @throws \Core\Application\InputException
     */
    public function __construct(string $bundleIds)
    {
        $this->bundleIds = $this->parseArrayAsJson($bundleIds, false);
        $this->validateArrayOfIntegers($this->bundleIds);
    }

    /**
     * @return array
     * @codeCoverageIgnore
     */
    public function getBundleIds(): array
    {
        return $this->bundleIds;
    }

}
