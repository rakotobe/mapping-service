<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/v1/map-addon/marketplace', [
    'uses' => 'AddonMapping\GetAddonMappingController@get',
    'as' => 'getAddonMapping'
]);

$router->get('/v1/map-addon/probiller', [
    'uses' => 'AddonMapping\GetAddonMappingController@getByProbillerAddonId',
    'as' => 'getAddonMappingByProbillerAddonId'
]);

$router->get('/v1/map-bundle/marketplace', [
    'uses' => 'BundleMapping\GetBundleMappingController@get',
    'as' => 'getBundleMapping'
]);

$router->get('/v1/map-bundle/probiller', [
    'uses' => 'BundleMapping\GetBundleMappingController@getByProbillerBundleId',
    'as' => 'getBundleMappingByProbillerBundleId'
]);

$router->group(['middleware' => 'inOffice'], function () use ($router) {
    $router->get('/v1/utilities/show-all-addon-mapping', 'UtilitiesController@showAllAddonMapping');
    $router->get('/v1/utilities/show-all-bundle-mapping', 'UtilitiesController@showAllBundleMapping');
});