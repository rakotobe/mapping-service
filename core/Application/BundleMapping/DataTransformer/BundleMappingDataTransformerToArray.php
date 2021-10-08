<?php


namespace Core\Application\BundleMapping\DataTransformer;


use Core\Domain\Bundle\Bundle;
use Core\Domain\Bundle\BundlesCollection;

class BundleMappingDataTransformerToArray implements BundleMappingDataTransformerInterface
{
    /** @var  BundlesCollection */
    private $bundlesCollection;

    /**
     * @param BundlesCollection $bundlesCollection
     */
    public function write(BundlesCollection $bundlesCollection)
    {
        $this->bundlesCollection = $bundlesCollection;
    }

    /**
     * @return array
     */
    public function read(): array
    {
        $bundlesOutput = [];
        foreach ($this->bundlesCollection as $bundle) {
            /** @var Bundle $bundle */
            $bundlesOutput[] = [
                'bundleId' => $bundle->getBundleId(),
                'probillerBundleId' => $bundle->getProbillerBundleId(),
                'internalName' => $bundle->getInternalName(),
                'title' => $bundle->getTitle(),
                'instance' => $bundle->getInstance(),
                'thumb' => $bundle->getThumb(),
                'description' => $bundle->getDescription(),
                'taxClassification' => $bundle->getTaxClassification(),
            ];
        }
        return $bundlesOutput;
    }

}
