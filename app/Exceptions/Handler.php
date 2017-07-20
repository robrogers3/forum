<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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

        if (app()->environment() == 'testing') {
            throw $exception;
        }

        if (false && config('app.debug')) {
            // if (false && is_a($exception, 'Illuminate\Validation\ValidationException') ||
            //     is_a($exception, 'Illuminate\Auth\AuthenticationException') ||
            //     is_a($exception, '\Symfony\Component\HttpKernel\Exception\HttpException') ||
            //     is_a($exception, 'Illuminate\Auth\Access\AuthorizationException')
            // ) {
            //     return parent::render($request, $exception);
            // }

            $whoops = new \Whoops\Run;

            if (request()->wantsJson()) {
                $whoops->pushHandler(new \Whoops\Handler\JsonResponseHandler);
            } else {
                $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
            }

            try {
                $code = $exception->getStatusCode();
            } catch (\Exception $e) {
                $code = 422;
            }
            
            $whoops->sendHttpCode($code);
            
            return $whoops->handleException($exception);
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }
}
