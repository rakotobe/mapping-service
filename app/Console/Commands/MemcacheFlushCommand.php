<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Core\Domain\CacheInterface;
use Illuminate\Console\Command;

class MemcacheFlushCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'memcache:flush';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flush the cache to reset';

    public function handle(CacheInterface $cache)
    {
        $environment = env('APP_ENV', '');
        if (in_array($environment, ['local', 'staging'])) {
            if ($cache->flushAll()) {
                $this->info('Cache flushed successfully');
            } else {
                $this->error('Cache was not flushed');
            }
        } else {
            $this->error("Error running " . $this->signature . " on environment " . $environment);
        }
    }
}
