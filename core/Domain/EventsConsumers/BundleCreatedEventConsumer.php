<?php

declare(strict_types=1);

namespace Core\Domain\EventsConsumers;

use Core\Domain\Bundle\Bundle;
use Core\Domain\RepositoryInterface;
use Core\Domain\Bundle\BundleRepositoryInterface;
use MindGeek\EventsStoreClient\EventInterface;
use MindGeek\OnePortalEvents\Simple\BundleCreated;

class BundleCreatedEventConsumer implements EventConsumerInterface
{
    /** @var BundleCreated */
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
        $service = new BundleCreatedEventConsumer($bundleRepository);
        return $service;
    }

    public function handle(EventInterface $event): bool
    {
        /** @var BundleCreated $event */
        if (!$event->getPurchaseBundleId()) {
            $bundle = new Bundle(
                $event->getBundleId(),
                $event->getInternalName(),
                $event->getTitle(),
                $event->getProbillerBundleId(),
                $event->getInstance(),
                $event->getThumb(),
                $event->getDescription(),
                $event->getTaxClassification()
            );
            return $this->bundleRepository->saveBundle($bundle);
        }

        return true;
    }
}
