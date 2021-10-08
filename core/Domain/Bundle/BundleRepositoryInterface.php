<?php

declare(strict_types=1);

namespace Core\Domain\Bundle;


interface BundleRepositoryInterface
{
    public function getBundle(int $bundleId):? Bundle;

    public function getBundleByProbillerBundleId(string $probillerBundleId): ?Bundle;

    public function saveBundle(Bundle $bundle): bool;

    public function deleteBundle(int $bundleId): bool;

    public function getAllBundleIds(): array;
}
