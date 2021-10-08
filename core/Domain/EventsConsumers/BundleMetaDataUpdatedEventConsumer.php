<?php

declare(strict_types=1);

namespace Core\Domain\EventsConsumers;

use Core\Domain\Bundle\Bundle;
use Core\Domain\Bundle\BundleRepositoryInterface;
use MindGeek\EventsStoreClient\EventInterface;
use MindGeek\OnePortalEvents\Enriched\BundleMetaDataUpdated;
use Core\Domain\InvalidInputException;

class BundleMetaDataUpdatedEventConsumer implements EventConsumerInterface
{
    /** @var BundleMetaDataUpdated */
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
        $service = new BundleMetaDataUpdatedEventConsumer($bundleRepository);
        return $service;
    }

    /**
     * @param EventInterface $event
     * @return bool
     * @throws InvalidInputException
     */
    public function handle(EventInterface $event): bool
    {
        /** @var BundleMetaDataUpdated $event */
        if (!$event->getPurchaseBundleId()) {
            $bundle = $this->bundleRepository->getBundle($event->getBundleId());
            if ($bundle) {
                $bundle->setTitle($event->getTitle());
                $bundle->setInternalName($event->getInternalName());
                $bundle->setInstance($event->getDefaultInstanceId());
                $bundle->setThumb($event->getDefaultThumbUrl());
                $bundle->setProbillerBundleId($event->getProbillerBundleId());
            } else {
                $bundle = new Bundle(
                    $event->getBundleId(),
                    $event->getInternalName(),
                    $event->getTitle(),
                    $event->getProbillerBundleId(),
                    $event->getDefaultInstanceId(),
                    $event->getDefaultThumbUrl(),
                    $event->getDescription(),
                    $event->getTaxClassification()
                );
            }
            return $this->bundleRepository->saveBundle($bundle);
        }

        return true;
    }
}
