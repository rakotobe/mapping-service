<?php

declare(strict_types=1);

namespace Tests\Integration\core\Application\AddonMapping;

use Core\Application\AddonMapping\DataTransformer\AddonMappingDataTransformerInterface;
use Core\Application\AddonMapping\DataTransformer\AddonMappingDataTransformerToArray;
use Core\Application\AddonMapping\GetAddonMappingByProbillerAddonIdInput;
use Core\Application\AddonMapping\GetAddonMappingHandler;
use Core\Application\AddonMapping\GetAddonMappingInput;
use Core\Infrastructure\Repository\AddonRepository;
use Tests\Integration\IntegrationTestCase;

class GetAddonMappingHandlerTest extends IntegrationTestCase
{
    /** @var AddonRepository|\PHPUnit_Framework_MockObject_MockObject */
    protected $addonRepository;

    /** @var AddonMappingDataTransformerInterface |\PHPUnit_Framework_MockObject_MockObject */
    protected $transformer;

    public function setup()
    {
        parent::setup();
        $this->addonRepository = $this->getMockBuilder(AddonRepository::class)->disableOriginalConstructor()->getMock();
        $this->transformer = new AddonMappingDataTransformerToArray();
    }

    /**
     * @test
     */
    public function get_addon_mapping_content()
    {
        $this->addonRepository->method('getAddon')->willReturn($this->getAddonContent());
        $getAddonMappingHandler = new GetAddonMappingHandler($this->addonRepository, $this->transformer);
        $input = new GetAddonMappingInput(json_encode([11]));
        $result = $getAddonMappingHandler->execute($input)->read();
        $this->assertIsArray($result);
    }

    /**
     * @test
     */
    public function get_addon_mapping_feature()
    {
        $this->addonRepository->method('getAddon')->willReturn($this->getAddonFeature());
        $getAddonMappingHandler = new GetAddonMappingHandler($this->addonRepository, $this->transformer);
        $input = new GetAddonMappingInput(json_encode([12]));
        $result = $getAddonMappingHandler->execute($input)->read();
        $this->assertIsArray($result);
    }

    /**
     * @test
     */
    public function get_addon_mapping_return_empty()
    {
        $getAddonMappingHandler = new GetAddonMappingHandler($this->addonRepository, $this->transformer);
        $input = new GetAddonMappingInput(json_encode([13]));
        $result = $getAddonMappingHandler->execute($input)->read();
        $this->assertIsArray($result);
        $this->assertEmpty($result['features']);
        $this->assertEmpty($result['contentGroups']);
    }


    /**
     * @test
     */
    public function get_addon_mapping_content_by_probiller_addon_id()
    {
        $this->addonRepository->method('getAddonByProbillerAddonId')->willReturn($this->getAddonContent());
        $getAddonMappingHandler = new GetAddonMappingHandler($this->addonRepository, $this->transformer);
        $input = new GetAddonMappingByProbillerAddonIdInput(json_encode(['PROB1231']));
        $result = $getAddonMappingHandler->executeByProbillerAddonId($input)->read();
        $this->assertIsArray($result);
    }

    /**
     * @test
     */
    public function get_addon_mapping_feature_by_probiller_addon_id()
    {
        $this->addonRepository->method('getAddonByProbillerAddonId')->willReturn($this->getAddonFeature());
        $getAddonMappingHandler = new GetAddonMappingHandler($this->addonRepository, $this->transformer);
        $input = new GetAddonMappingByProbillerAddonIdInput(json_encode(['PROB1231-343434']));
        $result = $getAddonMappingHandler->executeByProbillerAddonId($input)->read();
        $this->assertIsArray($result);
    }

    /**
     * @test
     */
    public function get_addon_mapping_return_empty_by_probiller_addon_id()
    {
        $getAddonMappingHandler = new GetAddonMappingHandler($this->addonRepository, $this->transformer);
        $input = new GetAddonMappingByProbillerAddonIdInput(json_encode(['PROB1231-21112']));
        $result = $getAddonMappingHandler->executeByProbillerAddonId($input)->read();
        $this->assertIsArray($result);
        $this->assertEmpty($result['features']);
        $this->assertEmpty($result['contentGroups']);
    }
}
