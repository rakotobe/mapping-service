<?php

namespace Core\Application\BundleMapping\DataTransformer;

use Core\Domain\Bundle\BundlesCollection;

interface BundleMappingDataTransformerInterface
{
    public function write(BundlesCollection $bundlesCollection);

    public function read();
}
