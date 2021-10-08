<?php

declare(strict_types=1);

namespace Core\Domain\EventsConsumers;

use Core\Domain\Addon\Addon;
use Core\Domain\Addon\AddonRepositoryInterface;
use MindGeek\EventsStoreClient\EventInterface;
use MindGeek\OnePortalEvents\Enriched\AddonUpdated;
use MindGeek\OnePortalEvents\Simple\AddonCreated;

class AddonCreatedEventConsumer implements EventConsumerInterface
{
    /** @var AddonUpdated */
    protected $event;

    /** @var AddonRepositoryInterface */
    protected $addonRepository;

    public function __construct(AddonRepositoryInterface $addonRepository)
    {
        $this->addonRepository = $addonRepository;
    }

    /**
     * @param AddonRepositoryInterface $addonRepository
     * @return EventConsumerInterface
     */
    public static function createEventConsumer($addonRepository): EventConsumerInterface
    {
        $service = new AddonCreatedEventConsumer($addonRepository);
        return $service;
    }

    public function handle(EventInterface $event): bool
    {
        /** @var AddonCreated $event */
        $addon = new Addon(
            $event->getAddonId(),
            $event->getProbillerAddonId(),
            $event->getInternalName(),
            $event->getTitle(),
            $event->getType(),
            $event->getThumb(),
            $event->getFeatureName(),
            $event->getContentGroupId()
        );
        $this->addonRepository->saveAddon($addon);
        return true;
    }
}
