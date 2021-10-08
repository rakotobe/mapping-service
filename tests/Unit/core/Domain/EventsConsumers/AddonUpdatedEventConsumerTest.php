<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\EventsConsumers;

use Core\Domain\EventsConsumers\AddonUpdatedEventConsumer;
use MindGeek\OnePortalEvents\Enriched\AddonUpdated;
use Tests\Unit\UnitTestCase;

class AddonUpdatedEventConsumerTest extends UnitTestCase
{
    /**
     * @test
     */
    public function createEventConsumer_should_return_instance_of_itself()
    {
        $this->ensure_createAddonEventConsumer_return_instance_of_service(AddonUpdatedEventConsumer::class);
    }

    /**
     * @test
     */
    public function handle_event()
    {
        $AddonRepository = $this->mockAddonRepository();
        $AddonRepository->method('getAddon')->willReturn($this->getAddonFeature());
        $consumer = new AddonUpdatedEventConsumer($AddonRepository);
        $result = $consumer->handle(new AddonUpdated(1,
            'addon name',
            'addon title',
            'probaddon123',
            'feature',
            '4k',
            null)
        );
        $this->assertTrue($result);
    }
}
