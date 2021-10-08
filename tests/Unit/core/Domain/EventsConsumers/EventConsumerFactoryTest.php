<?php

declare(strict_types=1);

namespace Tests\Unit\core\Domain\EventsConsumers;

use Core\Domain\Addon\AddonRepositoryInterface;
use Core\Domain\Bundle\BundleRepositoryInterface;
use Core\Domain\EventConsumptionException;
use Core\Domain\EventsConsumers\AddonCreatedEventConsumer;
use Core\Domain\EventsConsumers\BundleCreatedEventConsumer;
use Core\Domain\EventsConsumers\EventConsumerFactory;
use MindGeek\OnePortalEvents\Simple\AddonCreated;
use MindGeek\OnePortalEvents\Simple\BundleCreated;
use Tests\Unit\UnitTestCase;

class EventConsumerFactoryTest extends UnitTestCase
{
    /** @var EventConsumerFactory  */
    protected $eventConsumerFactory;

    /** @var AddonRepositoryInterface */
    protected $addonRepository;

    /** @var BundleRepositoryInterface */
    protected $bundleRepository;

    public function setUp()
    {
        parent::setUp();
        $this->eventConsumerFactory = new EventConsumerFactory();
        $this->addonRepository = $this->getMockBuilder(AddonRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->bundleRepository = $this->getMockBuilder(BundleRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @test
     */
    public function factory_throws_exception_if_events_handler_does_not_exist()
    {
        $this->expectException(EventConsumptionException::class);
        $this->eventConsumerFactory->factory(
            'BundleEventFailed',
            $this->addonRepository,
            $this->bundleRepository
        );
    }

    /**
     * @test
     */
    public function factory_should_works_with_valid_events()
    {
        $addonCreatedConsumer = $this->eventConsumerFactory->factory(
            AddonCreated::class,
            $this->addonRepository,
            $this->bundleRepository
        );
        $this->assertInstanceOf(AddonCreatedEventConsumer::class, $addonCreatedConsumer);

        $addonCreatedConsumer = $this->eventConsumerFactory->factory(
            BundleCreated::class,
            $this->addonRepository,
            $this->bundleRepository
        );
        $this->assertInstanceOf(BundleCreatedEventConsumer::class, $addonCreatedConsumer);
    }
}