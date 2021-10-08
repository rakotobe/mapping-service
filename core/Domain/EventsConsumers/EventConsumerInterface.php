<?php

declare(strict_types=1);

namespace Core\Domain\EventsConsumers;

use Core\Domain\Addon\AddonRepositoryInterface;
use Core\Domain\Bundle\BundleRepositoryInterface;
use MindGeek\EventsStoreClient\EventInterface;

interface EventConsumerInterface
{
    public function handle(EventInterface $event): bool;

    /**
     * @param AddonRepositoryInterface|BundleRepositoryInterface $repository
     * @return EventConsumerInterface
     */
    public static function createEventConsumer($repository): EventConsumerInterface;
}
