<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Bundle;

use Core\Domain\Addon\AddonsCollection;
use Core\Domain\InvalidArgumentException;
use Tests\Unit\UnitTestCase;

class AddonsCollectionTest extends UnitTestCase
{
    /**
     * @test
     */
    public function offsetSet_should_return_InvalidArgumentException()
    {
        $collection = new AddonsCollection();
        $this->expectException(InvalidArgumentException::class);
        $collection[] = 'throw an error exception';
    }
}
