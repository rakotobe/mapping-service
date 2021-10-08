<?php

declare(strict_types=1);

namespace Core\Application\BundleMapping;

use Core\Application\BundleMapping\DataTransformer\BundleMappingDataTransformerInterface;
use Core\Application\ErrorMessage;
use Core\Domain\Bundle\Bundle;
use Core\Domain\Bundle\BundleRepositoryInterface;
use Core\Domain\Bundle\BundlesCollection;
use MindGeek\MarketplaceLogger\LoggerException;
use MindGeek\MarketplaceLogger\LoggerProvider;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetBundleMappingHandler
{
    const MESSAGE_RESOURCE_NOT_FOUND = 'Resource not found';
    /**
     * @var BundleRepositoryInterface
     */
    private $repository;

    /**
     * @var BundleMappingDataTransformerInterface
     */
    private $transformer;

    /**
     * ArchiveCampaignHandler constructor.
     * @param BundleRepositoryInterface $repository
     * @param BundleMappingDataTransformerInterface $transformer
     */
    public function __construct(
        BundleRepositoryInterface $repository,
        BundleMappingDataTransformerInterface $transformer
    ) {
        $this->repository = $repository;
        $this->transformer = $transformer;
    }

    /**
     * @param GetBundleMappingInput $input
     * @return BundleMappingDataTransformerInterface
     * @throws LoggerException
     */
    public function execute(GetBundleMappingInput $input): BundleMappingDataTransformerInterface
    {
        $bundleCollection = new BundlesCollection();
        $bundleIds = $input->getBundleIds();
        foreach ($bundleIds as $bundleId) {
            /** @var Bundle $bundle */
            $bundle = $this->repository->getBundle((int) $bundleId);
            if ($bundle) {
                $bundleCollection->push($bundle);
            } else {
                $errorCode = ErrorMessage::BUNDLE_MAPPING_NOT_FOUND_ERROR;
                LoggerProvider::log(
                    LoggerProvider::LOG_LEVEL_WARNING,
                    ErrorMessage::ERROR_MESSAGE[$errorCode],
                    LoggerProvider::LOG_TAG_SYSTEM
                );
            }
        }

        $this->transformer->write($bundleCollection);
        return $this->transformer;
    }

    /**
     * @param GetBundleMappingByProbillerBundleIdInput $input
     * @return BundleMappingDataTransformerInterface
     * @throws LoggerException
     */
    public function executeByProbillerBundleId(GetBundleMappingByProbillerBundleIdInput $input): BundleMappingDataTransformerInterface
    {
        $bundleCollection = new BundlesCollection();
        $probillerBundleIds = $input->getProbillerBundleIds();
        foreach ($probillerBundleIds as $probillerBundleId) {
            /** @var Bundle $bundle */
            $bundle = $this->repository->getBundleByProbillerBundleId($probillerBundleId);
            if ($bundle) {
                $bundleCollection->push($bundle);
            } else {
                $errorCode = ErrorMessage::BUNDLE_MAPPING_NOT_FOUND_ERROR;
                LoggerProvider::log(
                    LoggerProvider::LOG_LEVEL_WARNING,
                    ErrorMessage::ERROR_MESSAGE[$errorCode],
                    LoggerProvider::LOG_TAG_SYSTEM
                );
            }
        }

        $this->transformer->write($bundleCollection);
        return $this->transformer;
    }
}
