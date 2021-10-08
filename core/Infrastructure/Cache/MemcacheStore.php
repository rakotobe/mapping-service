<?php

declare(strict_types=1);

namespace Core\Infrastructure\Cache;

use Core\Domain\CacheInterface;

/**
 * Class MemcacheStore
 * @package Core\Infrastructure\Cache
 */
class MemcacheStore implements CacheInterface
{
    /** @var \Memcached */
    protected $memcache = null;

    /**
     * @codeCoverageIgnore
     */
    public function __construct(\Memcached $memcached)
    {
        $this->memcache = $memcached;
    }

    /**
     * @codeCoverageIgnore
     */
    public function store(string $key, $value, int $ttl): bool
    {
        return $this->memcache->set($key, $value, $ttl);
    }

    /**
     * @codeCoverageIgnore
     */
    public function fetch(string $key)
    {
        $result = $this->memcache->get($key);
        if ($this->memcache->getResultCode() != \Memcached::RES_SUCCESS) {
            return null;
        }
        return $result;
    }

    /**
     * @codeCoverageIgnore
     */
    public function invalidate(string $key): bool
    {
        $this->memcache->delete($key);
        return true;
    }

    /**
     * @codeCoverageIgnore
     */
    public function flushAll(): bool
    {
        $this->memcache->flush();
        return true;
    }

    public function getAllKey()
    {
        return $this->memcache->getAllKeys();
    }
}
