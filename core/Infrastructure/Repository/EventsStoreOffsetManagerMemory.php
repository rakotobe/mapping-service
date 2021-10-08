<?php


declare(strict_types=1);

namespace Core\Infrastructure\Repository;

use Core\Domain\EventsStoreOffsetManagerInterface;
use Core\Domain\CacheInterface;

class EventsStoreOffsetManagerMemory implements EventsStoreOffsetManagerInterface
{
    const KEY_EVENTS_CONSUMER_ID = 'events-consume-id';
    const KEY_EVENTS_OFFSET = 'events-offset-mapping';
    const REMEMBER_FOREVER = 0;

    /** @var CacheInterface  */
    protected $cache;

    /**
     * @codeCoverageIgnore
     */
    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @codeCoverageIgnore
     */
    public function generateConsumerId(): string
    {
        $consumeId = $this->cache->fetch(self::KEY_EVENTS_CONSUMER_ID);
        if (is_null($consumeId)) {
            $consumeId = date('Ymd-') . mt_rand(100000, 999999);
            $this->cache->store(self::KEY_EVENTS_CONSUMER_ID, $consumeId, self::REMEMBER_FOREVER);
        }
        return $consumeId;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getOffset(): int
    {
        $offset = $this->cache->fetch(self::KEY_EVENTS_OFFSET);
        if (is_null($offset)) {
            $offset = 0;
            $this->setOffset($offset);
        }
        return $offset;
    }

    /**
     * @codeCoverageIgnore
     */
    public function setOffset(int $newOffset)
    {
        $this->cache->store(self::KEY_EVENTS_OFFSET, $newOffset, self::REMEMBER_FOREVER);
    }
}
