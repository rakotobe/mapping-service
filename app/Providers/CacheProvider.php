<?php

declare(strict_types=1);

namespace App\Providers;

use Core\Domain\CacheInterface;
use Core\Infrastructure\Cache\CacheManager;
use Core\Infrastructure\Cache\Exception;
use Illuminate\Support\Facades\Config;


class CacheProvider extends AppServiceProvider
{
    public function register()
    {
        $this->app->singleton(CacheInterface::class, function ($app) {
            $configurations = Config::get('cache.store');
            $cacheManager = new CacheManager($configurations);
            return $cacheManager->getStore();
        });
    }
}
