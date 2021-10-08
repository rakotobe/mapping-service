<?php

declare(strict_types=1);

namespace Tests\Unit;

use Core\Domain\Addon\Addon;
use Core\Infrastructure\Repository\AddonRepository;
use Core\Domain\Bundle\Bundle;
use Core\Infrastructure\Repository\BundleRepository;
use OnePortal\Catalog\Tests\TestCase;

class UnitTestCase extends TestCase
{
    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    public function mockAddonRepository()
    {
        return $this->getMockBuilder(AddonRepository::class)->disableOriginalConstructor()->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    public function mockBundleRepository()
    {
        return $this->getMockBuilder(BundleRepository::class)->disableOriginalConstructor()->getMock();
    }

    public function mockConsumer($object, $functionNames)
    {
        return $this->getMockBuilder(get_class($object))->disableOriginalConstructor()->setMethods($functionNames)->getMock();
    }

    protected function assertTwoObjectsAreEqual($expected, $actual)
    {
        $expectedPHPRepresentation = print_r($expected, true);
        $actualPHPRepresentation = print_r($actual, true);
        $this->assertEquals($expectedPHPRepresentation, $actualPHPRepresentation);
    }

    protected function getPrivateProperty($object, $propertyName)
    {
        $reflectionClass = new \ReflectionClass(get_Class($object));
        $property = $reflectionClass->getProperty($propertyName);
        $property->setAccessible(true);
        $output = $property->getValue($object);
        $property->setAccessible(false);
        return $output;
    }

    protected function setPrivateProperty($object, $propertyName, $propertyValue)
    {
        $reflectionClass = new \ReflectionClass(get_Class($object));
        $property = $reflectionClass->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($object, $propertyValue);
        $property->setAccessible(false);
    }

    protected function invokePrivateFunction($object, string $functionName, array $arguments)
    {
        $reflectionClass = new \ReflectionClass(get_class($object));
        $method = $reflectionClass->getMethod($functionName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $arguments);
    }

    public function ensure_createAddonEventConsumer_return_instance_of_service($class)
    {
        $addonRepository = $this->mockAddonRepository();
        $consumer = $this->getMockBuilder($class)->disableOriginalConstructor()->setMethods(null)->getMock();
        $this->setPrivateProperty($consumer, 'addonRepository', $addonRepository);
        $newAddon = $consumer->createEventConsumer($addonRepository);
        $this->assertInstanceOf($class, $newAddon);
    }

    public function ensure_createBundleEventConsumer_return_instance_of_service($class)
    {
        $bundleRepository = $this->mockBundleRepository();
        $consumer = $this->getMockBuilder($class)->disableOriginalConstructor()->setMethods(null)->getMock();
        $this->setPrivateProperty($consumer, 'bundleRepository', $bundleRepository);
        $newAddon = $consumer->createEventConsumer($bundleRepository);
        $this->assertInstanceOf($class, $newAddon);
    }

    public function invalidBundleAttributesProvider() {
        return [
            [32, '', 'Brazzers bundle', '7770', 5550, 'path/to/fake.jpg', 'description', 'taxClassification', 'purchase', null, 'Internal name should not be empty'],
            [32, 'BZ bundle', '', '7770', 5550, 'path/to/fake.jpg', 'description', 'taxClassification', 'purchase', null, 'Title should not be empty'],
            [32, 'BZ bundle', 'Brazzers bundle', '', 5550, 'path/to/fake.jpg', 'description', 'taxClassification', 'purchase', null , 'Probiller Id should not be empty'],
            [32, 'BZ bundle', 'Brazzers bundle', '7770', 5550, '', 'description', 'taxClassification', 'purchase', null , 'Thumb should not be empty'],
        ];
    }
}
