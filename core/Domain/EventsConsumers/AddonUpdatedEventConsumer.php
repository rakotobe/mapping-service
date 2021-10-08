<?php

declare(strict_types=1);

namespace Core\Domain\EventsConsumers;

use Core\Domain\Addon\AddonRepositoryInterface;
use MindGeek\EventsStoreClient\EventInterface;
use MindGeek\OnePortalEvents\Enriched\AddonUpdated;
use Core\Domain\InvalidInputException;

class AddonUpdatedEventConsumer implements EventConsumerInterface
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
        $service = new AddonUpdatedEventConsumer($addonRepository);
        return $service;
    }

    /**
     * @param EventInterface $event
     * @return bool
     * @throws InvalidInputException
     */
    public function handle(EventInterface $event): bool
    {
        /** @var AddonUpdated $event */
        $addon = $this->addonRepository->getAddon($event->getAddonId());
        $addon->setTitle($event->getTitle());
        $addon->setInternalName($event->getInternalName());
        $addon->setType($event->getType());
        $addon->setThumb($event->getThumb());
        $addon->setContentGroupId($event->getContentGroupId());
        $addon->setFeatureName($event->getFeatureName());
        $this->addonRepository->saveAddon($addon);
        return true;
    }
}
