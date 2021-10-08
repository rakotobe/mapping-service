<?php

declare(strict_types=1);

namespace Tests\Integration\core\Application\BundleMapping;

use Core\Application\BundleMapping\DataTransformer\BundleMappingDataTransformerInterface;
use Core\Application\BundleMapping\DataTransformer\BundleMappingDataTransformerToArray;
use Core\Application\BundleMapping\GetBundleMappingByProbillerBundleIdInput;
use Core\Application\BundleMapping\GetBundleMappingHandler;
use Core\Application\BundleMapping\GetBundleMappingInput;
use Core\Infrastructure\Repository\BundleRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\Integration\IntegrationTestCase;

class GetBundleMappingHandlerTest extends IntegrationTestCase
{
    /** @var BundleRepository|\PHPUnit_Framework_MockObject_MockObject */
    protected $bundleRepository;

    /** @var BundleMappingDataTransformerInterface |\PHPUnit_Framework_MockObject_MockObject */
    protected $transformer;

    public function setup()
    {
        parent::setup();
        $this->bundleRepository = $this->getMockBuilder(BundleRepository::class)->disableOriginalConstructor()->getMock();
        $this->transformer = new BundleMappingDataTransformerToArray();
    }

    /**
     * @test
     */
    public function get_bundle_mapping()
    {
        $this->bundleRepository->method('getBundle')->willReturn($this->getValidBundle());
        $getBundleMappingHandler = new GetBundleMappingHandler($this->bundleRepository, $this->transformer);
        $input = new GetBundleMappingInput(json_encode([11]));
        $result = $getBundleMappingHandler->execute($input)->read();
        $this->assertIsArray($result);
    }

    /**
     * @test
     */
    public function get_bundle_mapping_by_probiller_bundle_id()
    {
        $this->bundleRepository->method('getBundleByProbillerBundleId')->willReturn($this->getValidBundle());
        $getBundleMappingHandler = new GetBundleMappingHandler($this->bundleRepository, $this->transformer);
        $input = new GetBundleMappingByProbillerBundleIdInput(json_encode(['PROB1231']));
        $result = $getBundleMappingHandler->executeByProbillerBundleId($input)->read();
        $this->assertIsArray($result);
    }
}
