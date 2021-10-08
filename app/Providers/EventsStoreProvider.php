<?php

namespace App\Providers;

use Core\Domain\CacheInterface;
use Core\Domain\EventsStoreOffsetManagerInterface;
use Core\Infrastructure\Repository\EventsStoreOffsetManagerMemory;
use Illuminate\Support\Facades\Config;
use MindGeek\EventsStoreClient\Consumer;

class EventsStoreProvider extends AppServiceProvider
{
    public function register()
    {
        $this->app->singleton(Consumer::class, function ($app) {
            $client = new Consumer(Config::get('events_store.url'), Config::get('events_store.api_version'));
            return $client;
        });

        $this->app->singleton(EventsStoreOffsetManagerInterface::class, function ($app) {
            $cache = app(CacheInterface::class);
            return new EventsStoreOffsetManagerMemory($cache);
        });
    }
}
