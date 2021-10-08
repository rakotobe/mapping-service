<?php

declare(strict_types=1);

namespace Tests\Unit\core\Domain\Bundle;

use Core\Domain\Bundle\Bundle;
use Core\Domain\InvalidInputException;
use Tests\Unit\UnitTestCase;

class BundleTest extends UnitTestCase
{
    /**
     * @test
     * @dataProvider invalidBundleAttributesProvider
     *
     * @param $bundleId
     * @param $internalName
     * @param $title
     * @param $probillerBundleId
     * @param $instance
     * @param $thumb
     * @param string $description,
     * @param string $taxClassification
     * @param string $operation
     * @param int|null $purchaseBundleId
     * @param $expectedMessage
     */
    public function create_bundle_with_invalid_attributes_should_throw_exception(
        $bundleId,
        $internalName,
        $title,
        $probillerBundleId,
        $instance,
        $thumb,
        string $description,
        string $taxClassification,
        string $operation,
        ?int $purchaseBundleId,
        $expectedMessage
    ) {
        try {
            $bundle = new Bundle(
                $bundleId,
                $internalName,
                $title,
                $probillerBundleId,
                $instance,
                $thumb,
                $description,
                $taxClassification
            );
            $this->expectException(InvalidInputException::class);
        } catch(InvalidInputException $e) {
            $this->assertEquals($expectedMessage, $e->getMessage());
        }
    }
}
