<?php

namespace Core\Application\AddonMapping\DataTransformer;


use Core\Domain\Addon\Addon;
use Core\Domain\Addon\AddonsCollection;

class AddonMappingDataTransformerToArray implements AddonMappingDataTransformerInterface
{
    /** @var  AddonsCollection */
    private $addonsCollection;

    /** @var array $features */
    protected $features;

    /** @var array $contentGroups */
    protected $contentGroups;

    /**
     * @param AddonsCollection $addonsCollection
     */
    public function write(AddonsCollection $addonsCollection)
    {
        $this->addonsCollection = $addonsCollection;
        $this->features = [];
        $this->contentGroups = [];
    }

    /**
     * @return array
     */
    public function read(): array
    {
        $this->processByType();
        $addonsOutput = [
            'features' => $this->features,
            'contentGroups' => $this->contentGroups
        ];

        return $addonsOutput;
    }


    private function processByType()
    {
        foreach ($this->addonsCollection as $addon) {
            /** @var Addon $addon */
            if (strtolower($addon->getType()) == strtolower($addon::TYPE_FEATURE)) {
                $this->features[] = [
                    'addonId' => $addon->getAddonId(),
                    'probillerAddonId' => $addon->getProbillerAddonId(),
                    'featureName' => $addon->getFeatureName(),
                    'title' => $addon->getTitle(),
                    'thumb' => $addon->getThumb()
                ];
            }
            if (strtolower($addon->getType()) == strtolower($addon::TYPE_CONTENT)) {
                $this->contentGroups[] = [
                    'addonId' => $addon->getAddonId(),
                    'probillerAddonId' => $addon->getProbillerAddonId(),
                    'contentGroupId' => $addon->getContentGroupId(),
                    'title' => $addon->getTitle(),
                    'thumb' => $addon->getThumb()
                ];
            }
        }
    }
}
