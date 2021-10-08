<?php

namespace Core\Application\AddonMapping\DataTransformer;

use Core\Domain\Addon\AddonsCollection;


interface AddonMappingDataTransformerInterface
{
    public function write(AddonsCollection $addonsCollection);

    public function read();
}
