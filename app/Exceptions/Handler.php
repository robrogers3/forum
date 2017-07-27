<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    protected $messagingMap = [
        \Illuminate\Auth\AuthenticationException::class => 'Sorry we dont know who you are.',
        \Illuminate\Auth\Access\AuthorizationException::class => 'Sorry you are not authorized for this request.',
        \Symfony\Component\HttpKernel\Exception\HttpException::class => 'Humm, something got lost in transmission',
        \Illuminate\Database\Eloquent\ModelNotFoundException::class => 'We cannot find what you are looking for.',
        \Illuminate\Session\TokenMismatchException::class => 'Can we reconnect again?',
        \Illuminate\Validation\ValidationException::class => 'Hum, please check your form values some are invalid.'
    ];

    
    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $e = $exception;

        if (app()->environment() == 'testing') {
            throw $exception;
        }

        
        if (!$request->wantsJson()) {
            $whoops = new \Whoops\Run;
            $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
            return $whoops->handleException($exception);
        }

        return $this->prepareJsonResponse($request, $e)->send();
        
        if (is_a($exception, 'Illuminate\Validation\ValidationException')) {
            return parent::render($request, $exception);
        }

        if (
            is_a($exception, '\Symfony\Component\HttpKernel\Exception\NotFoundHttpException')
        ) {
            return response("We dont know what your are looking for", $e->getStatusCode());
        }
        
        if (
            is_a($exception, 'Illuminate\Auth\Access\AuthorizationException')
        ) {
            return response($exception->getMessage(), 403);
        }

        if (is_a($exception, 'Illuminate\Auth\AuthenticationException')) {
            return $this->unauthenticated($request, $exception);
        }

        $whoops = new \Whoops\Run;

        $whoops->pushHandler(new \Whoops\Handler\JsonResponseHandler);

        $whoops->sendHttpCode(400);

        return $whoops->handleException($exception);
    }

    protected function unauthenticated($request, $exception)
    {
        if ($request->expectsJson()) {
            return response('We dont know who you are!!', 401);
        }

        return redirect()->guest(route('login'));
    }

        /**
     * Convert the given exception to an array.
     *
     * @param  \Exception  $e
     * @return array
     */
    protected function convertExceptionToArray(Exception $e)
    {
        return config('app.debug') ? [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTrace(),
        ] : [
            'message' => $this->isHttpException($e) ? $e->getMessage() : 'Server Error',
        ];
    }

    protected function prepareJsonResponse($request, Exception $e)
    {
        $status = $this->isHttpException($e) ? $e->getStatusCode() : 500;
        $headers = $this->isHttpException($e) ? $e->getHeaders() : [];
        return JsonResponse::create(
            $this->convertExceptionToArray($e), $status, $headers
        );
    }
}
