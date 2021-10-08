<?php

declare(strict_types=1);

namespace App\Http\Controllers\AddonMapping;

use Core\Application\AddonMapping\GetAddonMappingByProbillerAddonIdInput;
use Core\Application\AddonMapping\GetAddonMappingHandler;
use Core\Application\AddonMapping\GetAddonMappingInput;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetAddonMappingController extends AddonMappingController
{
    /** @var GetAddonMappingHandler */
    private $handler;

    private $marketplaceAddonsRules = [
        self::INPUT_ADDON_IDS => 'required|string'
    ];

    private $probillerAddonsRules = [
        self::INPUT_PROBILLER_ADDON_IDS => 'required|string',
    ];

    /**
     * GetPurchaseCatalogHandler constructor.
     * @param GetAddonMappingHandler $handler
     */
    public function __construct(GetAddonMappingHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Core\Application\InputException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function get(Request $request): JsonResponse
    {
        $this->validate($request, $this->marketplaceAddonsRules);
        $addonIds = $request->input(self::INPUT_ADDON_IDS, '');

        $input = new GetAddonMappingInput($addonIds);

        $addonMapping = $this->handler->execute($input);
        return response()->json($addonMapping->read());
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Core\Application\InputException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getByProbillerAddonId(Request $request): JsonResponse
    {
        $this->validate($request, $this->probillerAddonsRules);
        $probillerAddonIds = $request->input(self::INPUT_PROBILLER_ADDON_IDS, '');

        $input = new GetAddonMappingByProbillerAddonIdInput($probillerAddonIds);

        $addonMapping = $this->handler->executeByProbillerAddonId($input);
        return response()->json($addonMapping->read());
    }
}
