<?php

declare(strict_types=1);

namespace Core\Infrastructure\Repository;

use Core\Domain\Addon\Addon;
use Core\Domain\Addon\AddonRepositoryInterface;
use Core\Domain\CacheInterface;

/**
 * Class StoreRepository
 * @package Core\Infrastructure\Repository
 */
class AddonRepository implements AddonRepositoryInterface
{
    public const REMEMBER_TTL = 0;//Forever
    public const ADDON_KEY = 'addon';
    public const ALL_ADDON_KEY = 'AllAddons';

    /** @var CacheInterface */
    protected $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getAddon(int $addonId): ?Addon
    {
        $key = self::ADDON_KEY . ':' . $addonId;
        $addon = $this->cache->fetch($key);
        return is_a($addon, Addon::class) ? $addon : null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getAddonByProbillerAddonId(string $probillerAddonId): ?Addon
    {
        $addonId = $this->getAddonIdByProbillerAddonId($probillerAddonId);
        if (!$addonId) {
            return null;
        }
        $key = self::ADDON_KEY . ':' . $addonId;
        $addon = $this->cache->fetch($key);
        return is_a($addon, Addon::class) ? $addon : null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function saveAddon(Addon $addon): bool
    {
        $key = self::ADDON_KEY . ':' . $addon->getAddonId();
        $result = $this->cache->store($key, $addon, self::REMEMBER_TTL);
        $this->addNewAddonId($addon->getAddonId());
        if (!$result) {
            return false;
        }
        $this->saveAddonIdByProbillerAddonId($addon->getAddonId(), $addon->getProbillerAddonId());
        return true;
    }

    /**
     * @codeCoverageIgnore
     */
    public function deleteAddon(int $addonId): bool
    {
        $key = self::ADDON_KEY . ':' . $addonId;
        if ($this->cache->invalidate($key)) {
            $this->cache->invalidate($key);
            return true;
        }
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function saveAddonIdByProbillerAddonId(int $addonId, string $probillerAddonId)
    {
        $key = self::ADDON_KEY . ':' . $probillerAddonId;
        $addonIdMap = array (
            'addonId' => $addonId,
            'probillerAddonId' => $probillerAddonId
        );
        $result = $this->cache->store($key, $addonIdMap, self::REMEMBER_TTL);
        if (!$result) {
            return false;
        }
        return true;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getAddonIdByProbillerAddonId(string $probillerAddonId): int
    {
        $key = self::ADDON_KEY . ':' . $probillerAddonId;
        $addonIdMap = $this->cache->fetch($key);
        if ($addonIdMap) {
            return $addonIdMap['addonId'];
        }
        return 0;
    }

    /** @codeCoverageIgnore  */
    public function getAllAddonIds(): array
    {
        $key = self::ALL_ADDON_KEY;
        $allAddons = $this->cache->fetch($key);
        if (!$allAddons) {
            $allAddons = [];
        }
        $keys = array_keys($allAddons);
        return array_combine($keys, $keys);
    }

    /** @codeCoverageIgnore  */
    protected function addNewAddonId(int $addonId): bool
    {
        $key = self::ALL_ADDON_KEY;
        $allAddons = $this->getAllAddonIds();
        $allAddons[$addonId] = $addonId;
        return $this->cache->store($key, $allAddons, self::REMEMBER_TTL);
    }
}
