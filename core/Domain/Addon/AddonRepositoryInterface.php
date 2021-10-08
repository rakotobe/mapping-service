<?php

declare(strict_types=1);

namespace Core\Domain\Addon;

interface AddonRepositoryInterface
{
    public function getAddon(int $addonId):? Addon;

    public function getAddonByProbillerAddonId(string $probillerAddonId): ?Addon;

    public function saveAddon(Addon $addon): bool;

    public function deleteAddon(int $addonId): bool;

    public function getAllAddonIds(): array;
}
