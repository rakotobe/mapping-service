<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\EventsConsumers;

use Core\Domain\EventsConsumers\AddonCreatedEventConsumer;
use Core\Domain\InvalidInputException;
use MindGeek\OnePortalEvents\Simple\AddonCreated;
use Tests\Unit\UnitTestCase;

class AddonCreatedEventConsumerTest extends UnitTestCase
{
    /**
     * @test
     */
    public function createEventConsumer_should_return_instance_of_itself()
    {
        $this->ensure_createAddonEventConsumer_return_instance_of_service(AddonCreatedEventConsumer::class);
    }

    /**
     * @test
     */
    public function handle_event()
    {
        $AddonRepository = $this->mockAddonRepository();
        $consumer = new AddonCreatedEventConsumer($AddonRepository);
        $result = $consumer->handle(new AddonCreated(1,
                'addon name',
                'addon title',
                'probaddon123',
                'feature',
                '4k',
                null)
        );
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function handle_invalid_input_title()
    {
        $AddonRepository = $this->mockAddonRepository();
        $consumer = new AddonCreatedEventConsumer($AddonRepository);
        $this->expectException(InvalidInputException::class);
        $result = $consumer->handle(new AddonCreated(1,
                'addon name',
                '',
                'probaddon123',
                'feature',
                '4k',
                null)
        );
    }

    /**
     * @test
     */
    public function handle_invalid_input_internal_name()
    {
        $AddonRepository = $this->mockAddonRepository();
        $consumer = new AddonCreatedEventConsumer($AddonRepository);
        $this->expectException(InvalidInputException::class);
        $result = $consumer->handle(new AddonCreated(1,
                '',
                'title',
                'probaddon123',
                'feature',
                '4k',
                null)
        );
    }

    /**
     * @test
     */
    public function handle_invalid_input_type()
    {
        $AddonRepository = $this->mockAddonRepository();
        $consumer = new AddonCreatedEventConsumer($AddonRepository);
        $this->expectException(InvalidInputException::class);
        $result = $consumer->handle(new AddonCreated(1,
                'addon name',
                'title',
                'probaddon123',
                '',
                '4k',
                null)
        );
    }
}
