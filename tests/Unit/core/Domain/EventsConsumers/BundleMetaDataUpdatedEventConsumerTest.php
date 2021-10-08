<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\EventsConsumers;

use Core\Domain\EventsConsumers\BundleMetaDataUpdatedEventConsumer;
use MindGeek\OnePortalEvents\Enriched\BundleMetaDataUpdated;
use Tests\Unit\UnitTestCase;

class BundleMetaDataUpdatedEventConsumerTest extends UnitTestCase
{
    /**
     * @test
     */
    public function createEventConsumer_should_return_instance_of_itself()
    {
        $this->ensure_createBundleEventConsumer_return_instance_of_service(BundleMetaDataUpdatedEventConsumer::class);
    }

    /**
     * @test
     */
    public function handle_event()
    {
        $bundleRepository = $this->mockBundleRepository();
        $bundleRepository->expects($this->once())
            ->method('getBundle')
            ->will($this->returnValue($this->getValidBundle()));
        $bundleRepository->expects($this->once())
            ->method('saveBundle')
            ->will($this->returnValue(true));
        $consumer = new BundleMetaDataUpdatedEventConsumer($bundleRepository);
        $result = $consumer->handle(new BundleMetaDataUpdated(
                32,
                'BZ bundle',
                'Brazzers bundle',
                '7770',
                5550,
                '/bundles/thumbs/bz_s.jpg',
                'purchase',
                null,
                'description',
                'taxClassification')
        );
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function handle_will_call_save_if_bundle_not_found()
    {
        $bundleRepository = $this->mockBundleRepository();
        $bundleRepository->expects($this->once())
            ->method('getBundle')
            ->will($this->returnValue(null));

        $bundleRepository->expects($this->once())
            ->method('saveBundle')
            ->will($this->returnValue(true));

        $consumer = new BundleMetaDataUpdatedEventConsumer($bundleRepository);
        $result = $consumer->handle(new BundleMetaDataUpdated(
                0,
                'BZ bundle',
                'Brazzers bundle',
                '7770',
                5550,
                '/bundles/thumbs/bz_s.jpg',
                'purchase',
                null,
                'description',
                'taxClassification')
        );
        $this->assertTrue($result);
    }

}
