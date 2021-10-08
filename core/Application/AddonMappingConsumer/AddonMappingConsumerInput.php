<?php

declare(strict_types=1);

namespace Core\Application\AddonMappingConsumer;

class AddonMappingConsumerInput
{
    /** @var \Closure */
    protected $infoLogger;

    /** @var \Closure */
    protected $warningLogger;

    /** @var string */
    protected $topicName;

    /** @var bool  */
    protected $flushAll = false;

    public function __construct(\Closure $infoLogger, \Closure $warningLogger, string $topicName, bool $flushAll)
    {
        $this->infoLogger = $infoLogger;
        $this->warningLogger = $warningLogger;
        $this->topicName = $topicName;
        $this->flushAll = $flushAll;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getInfoLogger(): \Closure
    {
        return $this->infoLogger;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getWarningLogger(): \Closure
    {
        return $this->warningLogger;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTopicName(): string
    {
        return $this->topicName;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getFlushAll(): bool
    {
        return $this->flushAll;
    }
}
