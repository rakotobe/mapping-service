<?php

declare(strict_types=1);

namespace Core\Application\AddonMapping;

use Core\Application\AddonMapping\DataTransformer\AddonMappingDataTransformerInterface;
use Core\Application\ErrorMessage;
use Core\Domain\Addon\Addon;
use Core\Domain\Addon\AddonRepositoryInterface;
use Core\Domain\Addon\AddonsCollection;
use MindGeek\MarketplaceLogger\LoggerException;
use MindGeek\MarketplaceLogger\LoggerProvider;

class GetAddonMappingHandler
{
    /** @var AddonRepositoryInterface */
    private $repository;

    /** @var AddonMappingDataTransformerInterface */
    private $transformer;

    public function __construct(
        AddonRepositoryInterface $repository,
        AddonMappingDataTransformerInterface $transformer
    ) {
        $this->repository = $repository;
        $this->transformer = $transformer;
    }

    /**
     * @param GetAddonMappingInput $input
     * @return AddonMappingDataTransformerInterface
     * @throws LoggerException
     */
    public function execute(GetAddonMappingInput $input): AddonMappingDataTransformerInterface
    {
        $addonCollection = new AddonsCollection();
        $addonIds = $input->getAddonIds();
        foreach ($addonIds as $addonId) {
            /** @var Addon $addon */
            $addon = $this->repository->getAddon((int) $addonId);
            if ($addon) {
                $addonCollection->push($addon);
            } else {
                $errorCode = ErrorMessage::ADDON_MAPPING_NOT_FOUND_ERROR;
                LoggerProvider::log(
                    LoggerProvider::LOG_LEVEL_WARNING,
                    ErrorMessage::ERROR_MESSAGE[$errorCode],
                    LoggerProvider::LOG_TAG_SYSTEM
                );
            }
        }

        $this->transformer->write($addonCollection);
        return $this->transformer;
    }

    /**
     * @param GetAddonMappingByProbillerAddonIdInput $input
     * @return AddonMappingDataTransformerInterface
     * @throws LoggerException
     */
    public function executeByProbillerAddonId(GetAddonMappingByProbillerAddonIdInput $input): AddonMappingDataTransformerInterface
    {
        $addonCollection = new AddonsCollection();
        $probillerAddonIds = $input->getProbillerAddonIds();
        foreach ($probillerAddonIds as $probillerAddonId) {
            /** @var Addon $addon */
            $addon = $this->repository->getAddonByProbillerAddonId($probillerAddonId);
            if ($addon) {
                $addonCollection->push($addon);
            } else {
                $errorCode = ErrorMessage::ADDON_MAPPING_NOT_FOUND_ERROR;
                LoggerProvider::log(
                    LoggerProvider::LOG_LEVEL_WARNING,
                    ErrorMessage::ERROR_MESSAGE[$errorCode],
                    LoggerProvider::LOG_TAG_SYSTEM
                );
            }
        }

        $this->transformer->write($addonCollection);
        return $this->transformer;
    }
}
