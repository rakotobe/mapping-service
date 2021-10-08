<?php

declare(strict_types=1);

namespace Core\Infrastructure\Repository;

use Core\Domain\Bundle\Bundle;
use Core\Domain\Bundle\BundleRepositoryInterface;
use Core\Domain\CacheInterface;

/**
 * Class BundleRepository
 * @package Core\Infrastructure\Repository
 */
class BundleRepository implements BundleRepositoryInterface
{
    public const REMEMBER_TTL = 0;//Forever
    public const BUNDLE_KEY = 'bundle';
    public const All_BUNDLE_KEY = 'allBundles';

    /** @var CacheInterface */
    protected $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getBundle(int $bundleId): ?Bundle
    {
        $key = self::BUNDLE_KEY . ':' . $bundleId;
        $bundle = $this->cache->fetch($key);
        return is_a($bundle, Bundle::class) ? $bundle : null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getBundleByProbillerBundleId(string $probillerBundleId): ?Bundle
    {
        $bundleId = $this->getBundleIdByProbillerBundleId($probillerBundleId);
        if (!$bundleId) {
            return null;
        }
        $key = self::BUNDLE_KEY . ':' . $bundleId;
        $bundle = $this->cache->fetch($key);
        return is_a($bundle, Bundle::class) ? $bundle : null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function saveBundle(Bundle $bundle): bool
    {
        $key = self::BUNDLE_KEY . ':' . $bundle->getBundleId();
        $result = $this->cache->store($key, $bundle, self::REMEMBER_TTL);
        $this->addNewBundleId($bundle->getBundleId());
        if (!$result) {
            return false;
        }
        $this->saveBundleIdByProbillerBundleId($bundle->getBundleId(), $bundle->getProbillerBundleId());
        return true;
    }

    /**
     * @codeCoverageIgnore
     */
    public function deleteBundle(int $bundleId): bool
    {
        $key = self::BUNDLE_KEY . ':' . $bundleId;
        if ($this->cache->invalidate($key)) {
            $this->cache->invalidate($key);
            return true;
        }
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function saveBundleIdByProbillerBundleId(int $bundleId, string $probillerBundleId)
    {
        $key = self::BUNDLE_KEY . ':' . $probillerBundleId;
        $bundleIdMap = array (
            'bundleId' => $bundleId,
            'probillerBundleId' => $probillerBundleId
        );
        $result = $this->cache->store($key, $bundleIdMap, self::REMEMBER_TTL);
        if (!$result) {
            return false;
        }
        return true;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getBundleIdByProbillerBundleId(string $probillerBundleId): int
    {
        $key = self::BUNDLE_KEY . ':' . $probillerBundleId;
        $bundleIdMap = $this->cache->fetch($key);
        if ($bundleIdMap) {
            return $bundleIdMap['bundleId'];
        }
        return 0;
    }

    /** @codeCoverageIgnore  */
    public function getAllBundleIds(): array
    {
        $key = self::All_BUNDLE_KEY;
        $allBundles = $this->cache->fetch($key);
        if (!$allBundles) {
            $allBundles = [];
        }
        $keys = array_keys($allBundles);
        return array_combine($keys, $keys);
    }

    /** @codeCoverageIgnore  */
    protected function addNewBundleId(int $bundleId): bool
    {
        $key = self::All_BUNDLE_KEY;
        $allBundles = $this->getAllBundleIds();
        $allBundles[$bundleId] = $bundleId;
        return $this->cache->store($key, $allBundles, self::REMEMBER_TTL);
    }
}
