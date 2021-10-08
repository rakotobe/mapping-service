<?php

declare(strict_types=1);

namespace Tests\Integration;

use Core\Domain\Addon\Addon;
use OnePortal\Catalog\Tests\TestCase;

class IntegrationTestCase extends TestCase
{
    protected function getAddonContent()
    {
        $addOn = new Addon(
            2,
            'prob123',
            'addon int',
            ' addon title',
            'content',
            'content-thumb.png',
            null,
            '101');
        return $addOn;
    }
}