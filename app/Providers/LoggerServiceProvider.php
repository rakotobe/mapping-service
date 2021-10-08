<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use MindGeek\MarketplaceLogger\Logger\CsvLogger;
use MindGeek\MarketplaceLogger\LoggerProvider;

class LoggerServiceProvider extends ServiceProvider
{
    /**
     * @throws \Exception
     */
    public function boot()
    {
        /** @var Request $request */
        $request = app(Request::class);
        LoggerProvider::initialize(
            new CsvLogger(config('logger.folder')),
            config('logger.service_name'),
            gethostname(),
            $request->server
                ? $request->server->get('REQUEST_METHOD') . ' ' . $request->server->get('HTTP_HOST') . $request->server->get('REQUEST_URI')
                : '',
            json_encode($request->request->all())
        );
    }
}