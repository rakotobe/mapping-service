<?php

use App\Console\Kernel;
use App\Exceptions\Handler;

require_once __DIR__ . '/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

try {
    /*
     * The following line used to be "->load()" instead of "->overload()"
     * If the function load is called instead of overload, the environment variables will be loaded in the following order:
     * 1. Server environment variables will be the first priority (e.g. passing an environment variable via Docker)
     * 2. $_SERVER variables, such as HOME, LANG, HTTP_HOST, etc
     * 3. Only if the requested env variable doesn't exist in the previous two places, then we look at the .env file
     *
     * When we use the function overload, the .env file will be the first priority
     */
    (new Dotenv\Dotenv(dirname(__DIR__)))->overload();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

$app = new Laravel\Lumen\Application(
    dirname(__DIR__)
);

$app->withFacades();

// $app->withEloquent();

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->configure('swagger-lume');
$app->configure('logger');

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    Kernel::class
);

$app->configure('events_store');
$app->configure('cache');
$app->configure('office-ip-addresses');

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

// $app->routeMiddleware([
//     'auth' => App\Http\Middleware\Authenticate::class,
// ]);

$app->routeMiddleware(['inOffice' => App\Http\Middleware\InOffice::class,]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/
//$app->register(AppServiceProvider::class);
//$app->register(Flipbox\LumenGenerator\LumenGeneratorServiceProvider::class);
//$app->register(Illuminate\Redis\RedisServiceProvider::class);
//$app->register(LaravelDoctrine\ORM\DoctrineServiceProvider::class);
//$app->register(LaravelDoctrine\Migrations\MigrationsServiceProvider::class);
$app->register(SwaggerLume\ServiceProvider::class);
$app->register(App\Providers\RepositoryProvider::class);
$app->register(App\Providers\EventsStoreProvider::class);
$app->register(App\Providers\CacheProvider::class);
$app->register(App\Providers\TransformerProvider::class);
$app->register(\App\Providers\LoggerServiceProvider::class);
/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__ . '/../routes/web.php';
});

return $app;
