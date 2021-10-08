<?php

declare(strict_types=1);

namespace Tests\Unit\core\Domain\Addon;

use Core\Domain\Addon\Addon;
use Core\Domain\InvalidInputException;
use Tests\Unit\UnitTestCase;

class AddonTest extends UnitTestCase
{
    /**
     * @test
     */
    public function create_addon_empty_thumb_should_throw_exception()
    {
        $this->expectException(InvalidInputException::class);

        $addon = new Addon(
            1,
            'a1b2c3d4-1234-abcd-89yz-sdfjhg324jhg',
            'internal name',
            'title',
            'content',
            '',
            null,
            '12322'
        );
    }
}
