<?php

namespace App\Exceptions;

use Core\Application\ApplicationException;
use Core\Application\InputException;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use MindGeek\MarketplaceLogger\LoggerException;
use MindGeek\MarketplaceLogger\LoggerProvider;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    public const NOT_FOUND_MESSAGE = 'Resource not found';

    /**
     * @param Request $request
     * @param Exception $exception
     * @return JsonResponse|Response
     * @throws LoggerException
     */
    public function render($request, Exception $exception)
    {
        if (env('APP_DEBUG')) {
            return parent::render($request, $exception);
        }

        if ($exception instanceof InputException) {
            LoggerProvider::log(LoggerProvider::LOG_LEVEL_WARNING, $this->getLogMessage($exception), LoggerProvider::LOG_TAG_SYSTEM);
            return $this->throwException($exception, Response::HTTP_BAD_REQUEST);
        }
        if ($exception instanceof ValidationException) {
            LoggerProvider::log(LoggerProvider::LOG_LEVEL_WARNING, $this->getLogMessage($exception), LoggerProvider::LOG_TAG_SYSTEM);
            return $this->throwException($exception, Response::HTTP_UNPROCESSABLE_ENTITY, collect($exception->errors()));
        }
        if ($exception instanceof ApplicationException) {
            LoggerProvider::log(LoggerProvider::LOG_LEVEL_ERROR, $this->getLogMessage($exception), LoggerProvider::LOG_TAG_SYSTEM);
            return $this->throwException($exception, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        if ($exception instanceof NotFoundHttpException || $exception instanceof MethodNotAllowedHttpException) {
            LoggerProvider::log(LoggerProvider::LOG_LEVEL_WARNING, $this->getLogMessage($exception), LoggerProvider::LOG_TAG_SYSTEM);
            return $this->throwException($exception, Response::HTTP_NOT_FOUND, self::NOT_FOUND_MESSAGE);
        }

        LoggerProvider::log(LoggerProvider::LOG_LEVEL_FATAL, $this->getLogMessage($exception), LoggerProvider::LOG_TAG_SYSTEM);
        return $this->throwException($exception, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    protected function throwException(Exception $e, int $code, string $errorMessage = null)
    {
        $response = ['message' => $errorMessage ?? $e->getMessage()];
        if ($e->getCode()) {
            $response['code'] = $e->getCode();
        }
        return response()->json($response, $code);
    }

    protected function getLogMessage(Exception $exception)
    {
        $logMessage = '';
        while ($exception) {
            $logMessage .= ($logMessage ? ' | Previous : ' : '')
                . 'Code : ' . $exception->getCode() . ' '
                . 'Class : ' . get_class($exception) . ' '
                . 'Error message : ' . $exception->getMessage();

            $exception = $exception->getPrevious();
        }

        return $logMessage;
    }
}
