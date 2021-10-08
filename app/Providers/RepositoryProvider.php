<?php

namespace App\Providers;

use Core\Domain\Addon\AddonRepositoryInterface;
use Core\Domain\Bundle\BundleRepositoryInterface;
use Core\Infrastructure\Repository\AddonRepository;
use Core\Infrastructure\Repository\BundleRepository;

class RepositoryProvider extends AppServiceProvider
{
    public function register()
    {
        $repositories = $this->getRepositories();
        foreach ($repositories as $abstraction => $implementation) {
            $this->app->bind(
                $abstraction,
                function () use ($implementation) {
                    return $repository = app($implementation);
                }
            );
        }
    }

    /**
     * @return array
     */
    private function getRepositories(): array
    {
        return [
            AddonRepositoryInterface::class => AddonRepository::class,
            BundleRepositoryInterface::class => BundleRepository::class
        ];
    }
}
