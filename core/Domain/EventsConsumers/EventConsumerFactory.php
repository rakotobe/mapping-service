<?php

declare(strict_types=1);

namespace Core\Domain\EventsConsumers;

use Core\Domain\Bundle\BundleRepositoryInterface;
use Core\Domain\EventConsumptionException;
use Core\Domain\Addon\AddonRepositoryInterface;
use MindGeek\OnePortalEvents\Enriched\AddonUpdated;
use MindGeek\OnePortalEvents\Simple\AddonCreated;
use MindGeek\OnePortalEvents\Simple\AddonDeleted;
use MindGeek\OnePortalEvents\Simple\BundleCreated;
use MindGeek\OnePortalEvents\Enriched\BundleMetaDataUpdated;
use MindGeek\OnePortalEvents\Simple\BundleDeleted;

class EventConsumerFactory
{
    const ADDON = 'Addon';
    const BUNDLE = 'Bundle';

    const HANDLERS_MAP = [
        AddonUpdated::class => [AddonUpdatedEventConsumer::class, self::ADDON],
        AddonCreated::class => [AddonCreatedEventConsumer::class, self::ADDON],
        AddonDeleted::class => [AddonDeletedEventConsumer::class, self::ADDON],
        BundleMetaDataUpdated::class => [BundleMetaDataUpdatedEventConsumer::class, self::BUNDLE],
        BundleCreated::class => [BundleCreatedEventConsumer::class, self::BUNDLE],
        BundleDeleted::class => [BundleDeletedEventConsumer::class, self::BUNDLE]
    ];

    /**
     * @param string $eventName
     * @param AddonRepositoryInterface $addonRepository
     * @param BundleRepositoryInterface $bundleRepository
     * @return EventConsumerInterface
     * @throws EventConsumptionException
     */
    public function factory(
        string $eventName,
        AddonRepositoryInterface $addonRepository,
        BundleRepositoryInterface $bundleRepository
    ): EventConsumerInterface {
        if (!isset(self::HANDLERS_MAP[$eventName])) {
            throw new EventConsumptionException('Event handler does not exist for event ' . $eventName);
        }
        list($className, $objectName) = self::HANDLERS_MAP[$eventName];
        $repository = $this->getRepository($objectName, $addonRepository, $bundleRepository);
        return call_user_func([$className, 'createEventConsumer'], $repository);
    }

    protected function getRepository(
        $objectName,
        $addonRepository,
        $bundleRepository
    ) {
        if ($objectName == self::ADDON) {
            return $addonRepository;
        }
        return $bundleRepository;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getEventsToListenTo(): array
    {
        return array_keys(self::HANDLERS_MAP);
    }
}
