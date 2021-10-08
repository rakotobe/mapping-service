<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\EventsConsumers;

use Core\Domain\EventsConsumers\AddonDeletedEventConsumer;
use MindGeek\OnePortalEvents\Simple\AddonDeleted;
use Tests\Unit\UnitTestCase;

class AddonDeletedEventConsumerTest extends UnitTestCase
{
    /**
     * @test
     */
    public function createEventConsumer_should_return_instance_of_itself()
    {
        $this->ensure_createAddonEventConsumer_return_instance_of_service(AddonDeletedEventConsumer::class);
    }

    /**
     * @test
     */
    public function handle_event()
    {
        $AddonRepository = $this->mockAddonRepository();
        $AddonRepository->method('deleteAddon')->willReturn(true);
        $consumer = new AddonDeletedEventConsumer($AddonRepository);
        $result = $consumer->handle(new AddonDeleted(2));
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function handle_not_addon_should_return_false()
    {
        $AddonRepository = $this->mockAddonRepository();
        $AddonRepository->method('getAddon')->willReturn(null);
        $consumer = new AddonDeletedEventConsumer($AddonRepository);
        $result = $consumer->handle(new AddonDeleted(3));
        $this->assertFalse($result);
    }
}
