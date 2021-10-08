<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Core\Domain\Addon\AddonRepositoryInterface;
use Core\Domain\Bundle\BundleRepositoryInterface;

class UtilitiesController extends Controller
{
    public function showAllAddonMapping(AddonRepositoryInterface $addonRepository)
    {
        $allAddonIds = $addonRepository->getAllAddonIds();
        foreach ($allAddonIds as $addonId) {
            $addon = $addonRepository->getAddon($addonId);
            if (!$addon) {
                continue;
            }
            dump($addon);
        }
    }

    public function showAllBundleMapping(BundleRepositoryInterface $bundleRepository)
    {
        $allBundleIds = $bundleRepository->getAllBundleIds();
        foreach ($allBundleIds as $bundleId) {
            $bundle = $bundleRepository->getBundle($bundleId);
            if (!$bundle) {
                continue;
            }
            dump($bundle);
        }
    }
}