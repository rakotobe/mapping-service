<?php

declare(strict_types=1);

namespace Core\Domain\EventsConsumers;

use Core\Domain\Addon\AddonRepositoryInterface;
use MindGeek\EventsStoreClient\EventInterface;
use MindGeek\OnePortalEvents\Enriched\AddonUpdated;
use MindGeek\OnePortalEvents\Simple\AddonDeleted;

class AddonDeletedEventConsumer implements EventConsumerInterface
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
        $service = new AddonDeletedEventConsumer($addonRepository);
        return $service;
    }

    public function handle(EventInterface $event): bool
    {
        /** @var AddonDeleted $event */
        return $this->addonRepository->deleteAddon($event->getAddonId());
    }
}
