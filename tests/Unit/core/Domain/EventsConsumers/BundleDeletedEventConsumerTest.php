<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\EventsConsumers;

use Core\Domain\EventsConsumers\BundleDeletedEventConsumer;
use MindGeek\OnePortalEvents\Simple\BundleDeleted;
use Tests\Unit\UnitTestCase;

class BundleDeletedEventConsumerTest extends UnitTestCase
{
    /**
     * @test
     */
    public function createEventConsumer_should_return_instance_of_itself()
    {
        $this->ensure_createBundleEventConsumer_return_instance_of_service(BundleDeletedEventConsumer::class);
    }

    /**
     * @test
     */
    public function handle_event()
    {
        $bundleRepository = $this->mockBundleRepository();
        $bundleRepository->expects($this->once())
            ->method('deleteBundle')
            ->will($this->returnValue(true));
        $consumer = new BundleDeletedEventConsumer($bundleRepository);
        $result = $consumer->handle(new BundleDeleted(2));
        $this->assertTrue($result);
    }
}
