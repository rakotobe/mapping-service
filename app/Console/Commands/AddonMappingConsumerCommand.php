<?php

namespace App\Console\Commands;

use Core\Application\AddonMappingConsumer\AddonMappingConsumerInput;
use Core\Application\AddonMappingConsumer\AddonMappingConsumerHandler;
use Core\Application\ApplicationException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class AddonMappingConsumerCommand extends Command
{
    const WAIT_BETWEEN_EVENTS_CONSUMPTION = 30;//In seconds

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'addonMapping:consume
                            {--c|cron : If set will be running as a cron with a limit}
                            {--f|flush : If set, cache will be flushed and events will be consumed again} 
                            {--D|no-delay : If set, we will not wait between consuming events}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consume the events from the events store to re-construct the catalog';

    /** @var AddonMappingConsumerHandler */
    protected $handler;

    /** @var string */
    protected $topicName;

    /** @var \Closure */
    protected $infoLogger;

    /** @var \Closure */
    protected $warningLogger;

    /** @var bool */
    protected $flushAll;

    /** @var bool */
    protected $noDelay;

    /** @var bool */
    protected $firstPass;

    /**
     * @param AddonMappingConsumerHandler $handler
     * @throws ApplicationException
     */
    public function handle(AddonMappingConsumerHandler $handler)
    {
        $this->handler = $handler;
        $obj = $this;
        $this->topicName = Config::get('events_store.topic_name');
        $this->infoLogger = function ($string) use ($obj) {
            $obj->info($string);
        };
        $this->warningLogger = function ($string) use ($obj) {
            $obj->error($string);
        };

        $this->flushAll = $this->option('flush');
        $this->noDelay = $this->option("no-delay");
        $this->firstPass = true;
        if ($this->option('cron')) {
            $i = 0;
            while ($i < 15) {
                $this->consumeEvents(self::WAIT_BETWEEN_EVENTS_CONSUMPTION);
                $i++;
            }
        } else {
            $startTime = time();
            while (true) {
                $this->consumeEvents(self::WAIT_BETWEEN_EVENTS_CONSUMPTION);
                $this->firstPass = false;
                if (time() - $startTime > 60) {
                    break;
                }
            }
        }
    }

    /**
     * @param float $usleepMultiplier
     * @throws ApplicationException
     */
    protected function consumeEvents(float $usleepMultiplier)
    {
        $input = new AddonMappingConsumerInput(
            $this->infoLogger,
            $this->warningLogger,
            $this->topicName,
            $this->flushAll && $this->firstPass
        );
        $output = $this->handler->execute($input);
        if (!$output) {
            $this->error('Something went wrong');
        }
        if (!$this->noDelay) {
            usleep((int)($usleepMultiplier * 1000000));
        }
    }
}
