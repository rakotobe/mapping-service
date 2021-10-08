<?php

namespace Core\Infrastructure\Cache;

class CacheManager
{
    /** @var array */
    protected $configurations;

    public function __construct($configurations)
    {
        $this->configurations = $configurations;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getStore()
    {
        $this->validateMemcacheConfigurations();
        // @codeCoverageIgnoreStart
        $memcached = new \Memcached();
        $memcached->addServer($this->configurations['server'], $this->configurations['port']);
        return new MemcacheStore($memcached);
        // @codeCoverageIgnoreEnd
    }

    /**
     * @codeCoverageIgnore
     */
    protected function validateMemcacheConfigurations()
    {
        if (!isset($this->configurations['server'])) {
            throw new Exception('Please provide a memcache server host');
        }
        if (!isset($this->configurations['port'])) {
            throw new Exception('Please provide a memcache server port');
        }
    }
}
