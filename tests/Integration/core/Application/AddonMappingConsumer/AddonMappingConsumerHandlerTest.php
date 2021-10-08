<?php

declare(strict_types=1);

namespace Tests\Integration\core\Application\AddonMappingConsumer;

use App\Console\Commands\AddonMappingConsumerCommand;
use Core\Application\AddonMappingConsumer\AddonMappingConsumerHandler;
use Core\Application\AddonMappingConsumer\AddonMappingConsumerInput;
use Core\Application\ApplicationException;
use Core\Domain\EventConsumptionException;
use Core\Domain\EventsConsumers\AddonCreatedEventConsumer;
use Core\Domain\EventsConsumers\EventConsumerFactory;
use Core\Infrastructure\Cache\MemcacheStore;
use Core\Infrastructure\Repository\AddonRepository;
use Core\Infrastructure\Repository\BundleRepository;
use Core\Infrastructure\Repository\EventsStoreOffsetManagerMemory;
use MindGeek\EventsStoreClient\Consumer;
use MindGeek\EventsStoreClient\EventsCollection;
use MindGeek\EventsStoreClient\Exceptions\ClientException;
use MindGeek\OnePortalEvents\Simple\AddonCreated;
use Tests\Integration\IntegrationTestCase;

class AddonMappingConsumerHandlerTest extends IntegrationTestCase
{
    /** @var Consumer|\PHPUnit_Framework_MockObject_MockObject */
    protected $consumer;

    /** @var AddonRepository \PHPUnit_Framework_MockObject_MockObject */
    protected $addonRepository;

    protected $bundleRepository;

    /** @var EventsStoreOffsetManagerMemory|\PHPUnit_Framework_MockObject_MockObject */
    protected $offsetManager;

    /** @var EventConsumerFactory|\PHPUnit_Framework_MockObject_MockObject */
    protected $eventFactory;

    /** @var MemcacheStore|\PHPUnit_Framework_MockObject_MockObject */
    protected $cache;

    public function setup()
    {
        parent::setup();
        $this->consumer = $this->getMockBuilder(Consumer::class)->disableOriginalConstructor()->getMock();

        $this->addonRepository = app(AddonRepository::class);
        $this->bundleRepository = app(BundleRepository::class);

        $this->offsetManager = $this->getMockBuilder(EventsStoreOffsetManagerMemory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->eventFactory = app(EventConsumerFactory::class);
        $this->cache = $this->getMockBuilder(MemcacheStore::class)->disableOriginalConstructor()->getMock();
    }

    /**
     * @test
     */
    public function execute_without_events_should_return_true()
    {
        $this->consumer->method('getErrors')->willReturn(['some errors']);
        $input = $this->getInput();
        $handler = $this->getAddonMappingConsumerHandler();
        $this->assertTrue($handler->execute($input));
    }

    /**
     * @test
     */
    public function execute_without_events_but_the_same_offset_should_return_true()
    {
        $input = $this->getInput();
        $handler = $this->getAddonMappingConsumerHandler();
        $this->offsetManager->method('getOffset')->willReturn(1);
        $this->consumer->method('getNewOffset')->willReturn(1);
        $this->consumer->method('consumeByOffset')->willReturn(new EventsCollection());
        $this->assertTrue($handler->execute($input));
    }

    /**
     * @test
     */
    public function execute_with_event_that_we_are_not_interested_in_should_call_logger()
    {
        $handler = $this->getAddonMappingConsumerHandler();
        /** @var AddonMappingConsumerInput|\PHPUnit_Framework_MockObject_MockObject $input */
        $input = $this->getMockBuilder(AddonMappingConsumerInput::class)
            ->disableOriginalConstructor()
            ->getMock();
        $infoLogger = function() {};
        $input->expects($this->once())->method('getInfoLogger')->willReturn($infoLogger);
        $this->offsetManager->method('getOffset')->willReturn(1);
        $this->consumer->method('getNewOffset')->willReturn(2);
        $eventsCollection = new EventsCollection();
        $eventsCollection[0] = $this->getMockBuilder(AddonCreated::class)->disableOriginalConstructor()->getMock();
        $this->consumer->method('consumeByOffset')->willReturn($eventsCollection);
        $this->assertTrue($handler->execute($input));
    }

    /**
     * @test
     */
    public function execute_should_throw_exception_when_consumer_failed_to_consume_events()
    {
        $input = $this->getInput();
        $handler = $this->getAddonMappingConsumerHandler();
        $this->consumer->method('consumeByOffset')->willThrowException(new ClientException());
        $this->expectException(ApplicationException::class);
        $handler->execute($input);
    }

    /**
     * @test
     */
    public function execute_without_events_but_with_different_offset_should_return_true()
    {
        $input = $this->getInput();
        $handler = $this->getAddonMappingConsumerHandler();
        $this->offsetManager->method('getOffset')->willReturn(1);
        $this->consumer->method('getNewOffset')->willReturn(2);
        $this->consumer->method('consumeByOffset')->willReturn(new EventsCollection());
        $this->assertTrue($handler->execute($input));
    }

    /**
     * @test
     */
    public function execute_with_events_should_return_true()
    {
        $input = $this->getInput();
        $handler = $this->getAddonMappingConsumerHandler();
        $this->offsetManager->method('getOffset')->willReturn(1);
        $this->consumer->method('getNewOffset')->willReturn(2);
        $eventsCollection = new EventsCollection();
        $eventsCollection[0] = $this->getEvent();
        $this->consumer->method('consumeByOffset')->willReturn($eventsCollection);
        $this->assertTrue($handler->execute($input));
    }

    /**
     * @test
     */
    public function execute_with_events_should_throw_exception_when_factory_cannot_create_object()
    {
        /** @var AddonMappingConsumerInput|\PHPUnit_Framework_MockObject_MockObject $input */
        $input = $this->getMockBuilder(AddonMappingConsumerInput::class)->disableOriginalConstructor()->getMock();
        $eventsCollection = new EventsCollection();
        $eventsCollection[0] = $this->getMockBuilder(AddonCreated::class)->disableOriginalConstructor()->getMock();
        /** @var EventConsumerFactory|\PHPUnit_Framework_MockObject_MockObject eventFactory */
        $this->eventFactory = $this->getMockBuilder(EventConsumerFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->eventFactory->method('factory')->willThrowException(new EventConsumptionException());
        $this->eventFactory->method('getEventsToListenTo')->willReturn(array(get_class($eventsCollection[0])));
        $handler = $this->getAddonMappingConsumerHandler();
        $this->offsetManager->method('getOffset')->willReturn(1);
        $this->consumer->method('getNewOffset')->willReturn(2);
        $this->consumer->method('consumeByOffset')->willReturn($eventsCollection);
        $this->expectException(ApplicationException::class);
        $handler->execute($input);
    }

    /**
     * @test
     */
    public function execute_with_events_should_throw_exception()
    {
        /** @var EventConsumerFactory|\PHPUnit_Framework_MockObject_MockObject eventFactory */
        $this->eventFactory = $this->getMockBuilder(EventConsumerFactory::class)->disableOriginalConstructor()->getMock();
        /** @var AddonMappingConsumerInput|\PHPUnit_Framework_MockObject_MockObject $input */
        $input = $this->getMockBuilder(AddonMappingConsumerInput::class)->disableOriginalConstructor()->getMock();
        $handler = $this->getAddonMappingConsumerHandler();
        $this->offsetManager->method('getOffset')->willReturn(1);
        $this->consumer->method('getNewOffset')->willReturn(2);
        $eventsCollection = new EventsCollection();
        $eventsCollection[0] = $this->getMockBuilder(AddonCreated::class)->disableOriginalConstructor()->getMock();
        $this->consumer->method('consumeByOffset')->willReturn($eventsCollection);
        $addonConsumer = $this->getMockBuilder(AddonCreatedEventConsumer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->eventFactory->method('factory')->willReturn($addonConsumer);
        $this->eventFactory->method('getEventsToListenTo')->willReturn(array(get_class($eventsCollection[0])));
        $addonConsumer->method('handle')->willReturn(false);
        $this->expectException(ApplicationException::class);
        $handler->execute($input);
    }

    protected function getAddonMappingConsumerHandler()
    {
        return new AddonMappingConsumerHandler(
            $this->consumer,
            $this->addonRepository,
            $this->bundleRepository,
            $this->offsetManager,
            $this->eventFactory,
            $this->cache
        );
    }

    protected function getEvent()
    {
        $event = new AddonCreated(
            1002,
            'addon ',
            'internal addon',
            'prob001',
            'feature',
            'download 4k',
            null
        );
        return $event;
    }

    protected function getInput()
    {
        $consumerCommand = $this->getMockBuilder(AddonMappingConsumerCommand::class)
            ->disableOriginalConstructor()
            ->getMock();
        $infoLogger = function ($string) use ($consumerCommand) {
            $consumerCommand->info($string);
        };
        $warningLogger = function ($string) use ($consumerCommand) {
            $consumerCommand->warn($string);
        };

        $input = new AddonMappingConsumerInput($infoLogger, $warningLogger,'test-topic-integration',true);
        return $input;
    }
}
