<?php

declare(strict_types=1);

namespace Core\Domain;

interface EventsStoreOffsetManagerInterface
{
    public function generateConsumerId(): string;

    public function getOffset(): int;

    public function setOffset(int $newOffset);
}
