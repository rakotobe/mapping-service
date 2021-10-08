<?php

declare(strict_types=1);

namespace Core\Domain;

interface CacheInterface
{
    /**
     * @param string $key
     * @param $value
     * @param int $ttl
     * @return bool
     */
    public function store(string $key, $value, int $ttl): bool;

    /**
     * @param string $key
     * @return mixed
     */
    public function fetch(string $key);

    /**
     * @param string $key
     * @return mixed
     */
    public function invalidate(string $key): bool;

    /**
     * @return bool
     */
    public function flushAll(): bool;

    public function getAllKey();
}
