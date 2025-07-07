<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with custom log levels.
     */
    protected $levels = [];

    /**
     * A list of the exception types that are not reported.
     */
    protected $dontReport = [];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        //
    }

    public function render($request, Throwable $exception)
    {
        // Редирект на маршрут home при 404
        if ($exception instanceof NotFoundHttpException) {
            return redirect()->route('home');
        }

        // Редирект на маршрут home при інших кодах (403, 419, 500)
        if ($exception instanceof HttpExceptionInterface) {
            $status = $exception->getStatusCode();
            if (in_array($status, [403, 419, 500])) {
                return redirect()->route('home');
            }
        }

        return parent::render($request, $exception);
    }
}
