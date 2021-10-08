<?php

declare(strict_types = 1);

namespace OnePortal\Catalog\Tests;

use Core\Domain\Addon\Addon;
use Core\Domain\Bundle\Bundle;
use Laravel\Lumen\Testing\TestCase as LaravelTestCase;
use MindGeek\MarketplaceLogger\Logger\CsvLogger;
use MindGeek\MarketplaceLogger\Logger\LoggerInterface;
use MindGeek\MarketplaceLogger\LoggerProvider;
use PHPUnit\Framework\MockObject\MockObject;

abstract class TestCase extends LaravelTestCase
{
    public function setUp()
    {
        parent::setUp();
        /** @var LoggerInterface|MockObject $logger */
        $logger = $this->createMock(CsvLogger::class);
        $logger->method('log')->willReturn(null);
        LoggerProvider::initialize($logger, 'test', 'test', (new \DateTime())->format('Y-m-d H:i'), '');
    }

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    protected function getAddonFeature()
    {
        $addOn = new Addon(
            1,
            'prob123',
            'addon int',
            ' addon title',
            'feature',
            'feature-thumb.png',
            'download',
            null);
        return $addOn;
    }

    protected function getValidBundle() {
        $bundle = new Bundle(
            32,
            'BZ bundle',
            'Brazzers bundle',
            '7770',
            5550,
            'path/to/fake.jpg',
            'description',
            'taxClassification'
        );
        return $bundle;
    }
}
