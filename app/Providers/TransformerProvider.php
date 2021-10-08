<?php

namespace App\Providers;

use Core\Application\AddonMapping\DataTransformer\AddonMappingDataTransformerInterface;
use Core\Application\AddonMapping\DataTransformer\AddonMappingDataTransformerToArray;
use Core\Application\BundleMapping\DataTransformer\BundleMappingDataTransformerInterface;
use Core\Application\BundleMapping\DataTransformer\BundleMappingDataTransformerToArray;

class TransformerProvider extends AppServiceProvider
{
    public function register()
    {
        $transformers = $this->getTransformers();
        foreach ($transformers as $abstraction => $implementation) {
            $this->app->singleton(
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
    private function getTransformers(): array
    {
        return [
            AddonMappingDataTransformerInterface::class => AddonMappingDataTransformerToArray::class,
            BundleMappingDataTransformerInterface::class => BundleMappingDataTransformerToArray::class
        ];
    }
}
