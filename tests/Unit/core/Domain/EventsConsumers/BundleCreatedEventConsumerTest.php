<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\EventsConsumers;

use Core\Domain\Bundle\BundleRepositoryInterface;
use Core\Domain\EventsConsumers\BundleCreatedEventConsumer;
use Core\Domain\InvalidInputException;
use MindGeek\OnePortalEvents\Simple\BundleCreated;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\Unit\UnitTestCase;

class BundleCreatedEventConsumerTest extends UnitTestCase
{
    /**
     * @test
     */
    public function createEventConsumer_should_return_instance_of_itself()
    {
        $this->ensure_createBundleEventConsumer_return_instance_of_service(BundleCreatedEventConsumer::class);
    }

    /**
     * @test
     */
    public function handle_event() {
        /** @var BundleRepositoryInterface|MockObject $bundleRepository */
        $bundleRepository = $this->mockBundleRepository();
        $bundleRepository->expects($this->once())
            ->method('saveBundle')
            ->will($this->returnValue(true));
        $consumer = new BundleCreatedEventConsumer($bundleRepository);
        $result = $consumer->handle(new BundleCreated(
                32,
                'BZ bundle',
                'Brazzers bundle',
                '7770',
                5550,
                '/bundles/thumbs/bz_s.jpg',
                'description',
                'taxClassification',
                'purchase',
                null
            )
        );
        $this->assertTrue($result);
    }

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
     * @param $expectedMessage
     */
    public function handle_should_throw_exception_when_invalid_input(
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
        /** @var BundleRepositoryInterface|MockObject $bundleRepository */
        $bundleRepository = $this->mockBundleRepository();
        try {
            $consumer = new BundleCreatedEventConsumer($bundleRepository);
            $result = $consumer->handle(
                new BundleCreated(
                    $bundleId,
                    $internalName,
                    $title,
                    $probillerBundleId,
                    $instance,
                    $thumb,
                    $description,
                    $taxClassification,
                    $operation,
                    $purchaseBundleId
                )
            );
            $this->expectException(InvalidInputException::class);
        } catch(InvalidInputException $e) {
            $this->assertEquals($expectedMessage, $e->getMessage());
        }
    }
}
