<?php

declare(strict_types=1);

namespace Core\Domain\EventsConsumers;

use Core\Domain\Bundle\BundleRepositoryInterface;
use MindGeek\EventsStoreClient\EventInterface;
use MindGeek\OnePortalEvents\Simple\BundleDeleted;

class BundleDeletedEventConsumer implements EventConsumerInterface
{
    /** @var BundleDeleted */
    protected $event;

    /** @var BundleRepositoryInterface */
    protected $bundleRepository;

    public function __construct(BundleRepositoryInterface $bundleRepository)
    {
        $this->bundleRepository = $bundleRepository;
    }

    /**
     * @param BundleRepositoryInterface $bundleRepository
     * @return EventConsumerInterface
     */
    public static function createEventConsumer($bundleRepository): EventConsumerInterface
    {
        $service = new BundleDeletedEventConsumer($bundleRepository);
        return $service;
    }

    public function handle(EventInterface $event): bool
    {
        /** @var BundleDeleted $event */
        return $this->bundleRepository->deleteBundle($event->getBundleId());
    }
}
