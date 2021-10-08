<?php

declare(strict_types=1);

namespace Core\Application\AddonMappingConsumer;

use Core\Application\ApplicationException;
use Core\Domain\CacheInterface;
use Core\Domain\EventsConsumers\EventConsumerFactory;
use Core\Domain\EventsStoreOffsetManagerInterface;
use Core\Domain\Addon\AddonRepositoryInterface;
use Core\Domain\Bundle\BundleRepositoryInterface;
use MindGeek\EventsStoreClient\Consumer;
use MindGeek\EventsStoreClient\EventInterface;
use MindGeek\EventsStoreClient\EventsCollection;
use MindGeek\EventsStoreClient\Exceptions\ClientException;
use MindGeek\EventsStoreClient\Exceptions\ServerException;

class AddonMappingConsumerHandler
{
    const NB_EVENTS_TO_BE_CONSUMED = 50;

    /** @var Consumer */
    protected $consumer;

    /** @var AddonRepositoryInterface */
    protected $addonRepository;

    /** @var BundleRepositoryInterface */
    protected $bundleRepository;

    /** @var EventsStoreOffsetManagerInterface */
    protected $offsetManager;

    /** @var EventConsumerFactory */
    protected $eventConsumerFactory;

    /** @var CacheInterface */
    protected $cache;

    public function __construct(
        Consumer $consumer,
        AddonRepositoryInterface $addonRepository,
        BundleRepositoryInterface $bundleRepository,
        EventsStoreOffsetManagerInterface $offsetManager,
        EventConsumerFactory $eventConsumerFactory,
        CacheInterface $cache
    ) {
        $this->consumer = $consumer;
        $this->addonRepository = $addonRepository;
        $this->bundleRepository = $bundleRepository;
        $this->offsetManager = $offsetManager;
        $this->eventConsumerFactory = $eventConsumerFactory;
        $this->cache = $cache;
    }

    /**
     * @param AddonMappingConsumerInput $input
     * @return bool
     * @throws ApplicationException
     */
    public function execute(AddonMappingConsumerInput $input): bool
    {
        if ($input->getFlushAll()) {
            $this->cache->flushAll();
        }
        $offset = $this->offsetManager->getOffset();
        $events = $this->consumeEventsByOffset($input, $offset);
        $newOffset = $this->getEventsNewOffset();

        if ($offset == $newOffset) {
            //No events were produced at the moment
            $input->getInfoLogger()->call($this, 'No events were produced at the moment');
            return true;
        }
        $eventsToListenTo = $this->eventConsumerFactory->getEventsToListenTo();
        foreach($events as $oneEvent) {
            if (in_array(get_class($oneEvent), $eventsToListenTo)) {
                $handlerResult = $this->handleEvent($oneEvent, $input->getInfoLogger(), $input->getWarningLogger());
                if (!$handlerResult) {
                    throw new ApplicationException('Something went wrong with handling the event');
                }
            } else {
                $input->getInfoLogger()->call(
                    $this,
                    'Event of type '.get_class($oneEvent).' was produced, but we\'re not interested in'
                );
            }
        }

        $this->offsetManager->setOffset($newOffset);
        return true;
    }

    /**
     * @param AddonMappingConsumerInput $input
     * @param int $offset
     * @return EventsCollection
     * @throws ApplicationException
     */
    protected function consumeEventsByOffset(AddonMappingConsumerInput $input, int $offset): EventsCollection
    {
        try {
            $events = $this->consumer->consumeByOffset(
                [],
                $input->getTopicName(),
                $offset,
                self::NB_EVENTS_TO_BE_CONSUMED
            );
            return $events;
        } catch (ClientException|ServerException $e) {
            $message = '';
            if (!empty($this->consumer->getErrors())) {
                $message = 'Errors while consuming events: ' . implode("\n", $this->consumer->getErrors());
            }
            $message .= "\n\n" . $e->getMessage();
            throw new ApplicationException($message);
        }
    }

    /**
     * @return int
     * @throws ApplicationException
     * @codeCoverageIgnore
     */
    protected function getEventsNewOffset(): int
    {
        try {
            return $this->consumer->getNewOffset();
        } catch (ClientException $e) {
            throw new ApplicationException($e->getMessage());
        }
    }

    /**
     * @param EventInterface $event
     * @param \Closure $infoLogger
     * @param \Closure $warningLogger
     * @return bool
     * @throws ApplicationException
     */
    protected function handleEvent(EventInterface $event, \Closure $infoLogger, \Closure $warningLogger): bool
    {
        //The purpose of the logger is to show messages when running the consumer from the command line.
        //The logger should be switched to OnePortal logger. This way, we can insert the consumer
        // messages in elastic search
        $infoLogger->call(
            $this,
            'Consuming event: ' . get_class($event)
        );
        try {
            $handler = $this->eventConsumerFactory->factory(get_class($event), $this->addonRepository, $this->bundleRepository);
            return $handler->handle($event);
        } catch (\Exception $e) {
            $warningLogger->call($this, 'Error handling ' . get_class($event) . ' event: ' . $e->getMessage());
            throw new ApplicationException($e->getMessage());
        }
    }
}
