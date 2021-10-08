<?php

declare(strict_types=1);

namespace App\Http\Controllers\BundleMapping;

use Core\Application\BundleMapping\GetBundleMappingByProbillerBundleIdInput;
use Core\Application\BundleMapping\GetBundleMappingHandler;
use Core\Application\BundleMapping\GetBundleMappingInput;
use Core\Application\InputException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use MindGeek\MarketplaceLogger\LoggerException;

class GetBundleMappingController extends BundleMappingController
{
    /** @var GetBundleMappingHandler */
    private $handler;

    /**
     * GetPurchaseCatalogHandler constructor.
     * @param GetBundleMappingHandler $handler
     */
    public function __construct(GetBundleMappingHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws LoggerException
     * @throws ValidationException
     * @throws InputException
     */
    public function get(Request $request): JsonResponse
    {
        $this->validate($request, [self::INPUT_BUNDLE_IDS => 'required|string']);
        $bundleIds = $request->input(self::INPUT_BUNDLE_IDS);

        $input = new GetBundleMappingInput(
            $bundleIds
        );

        $bundleMapping = $this->handler->execute($input);
        return response()->json($bundleMapping->read());
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Core\Application\InputException
     * @throws ValidationException
     * @throws LoggerException
     */
    public function getByProbillerBundleId(Request $request): JsonResponse
    {
        $this->validate($request, [self::INPUT_PROBILLER_BUNDLE_IDS => 'required|string']);
        $probillerBundleIds = $request->input(self::INPUT_PROBILLER_BUNDLE_IDS);

        $input = new GetBundleMappingByProbillerBundleIdInput(
            $probillerBundleIds
        );

        $bundleMapping = $this->handler->executeByProbillerBundleId($input);
        return response()->json($bundleMapping->read());
    }
}
