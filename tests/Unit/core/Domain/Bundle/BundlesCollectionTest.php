<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Bundle;

use Core\Domain\Bundle\BundlesCollection;
use Core\Domain\InvalidArgumentException;
use Tests\Unit\UnitTestCase;

class BundleCollectionTest extends UnitTestCase
{
    /**
     * @test
     */
    public function offsetSet_should_return_InvalidArgumentException()
    {
        $collection = new BundlesCollection();
        $this->expectException(InvalidArgumentException::class);
        $collection[] = 'throw an error exception';
    }
}
