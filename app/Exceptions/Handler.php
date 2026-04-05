<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use GuzzleHttp\Exception\ClientException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Exceptions that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
        AuthenticationException::class, 
    ];

    /**
     * Report exception
     * 
     * 
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render exception into response
     */
    public function render($request, Throwable $exception)
    {
        // 🔥 SHOW REAL ERROR IN DEBUG MODE
        if (env('APP_DEBUG', false)) {
            return parent::render($request, $exception);
        }

        // 🔥 HTTP Errors
        if ($exception instanceof HttpException) {
            return response()->json([
                'error' => Response::$statusTexts[$exception->getStatusCode()] ?? 'Error'
            ], $exception->getStatusCode());
        }

        // 🔥 Model Not Found
        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'error' => 'Resource not found'
            ], 404);
        }

        // 🔥 Validation Errors
        if ($exception instanceof ValidationException) {
            return response()->json([
                'error' => 'Validation failed',
                'details' => $exception->validator->errors()
            ], 422);
        }

        // 🔥 Authorization
        if ($exception instanceof AuthorizationException) {
            return response()->json([
                'error' => $exception->getMessage()
            ], 403);
        }

        // 🔥 Authentication
        if ($exception instanceof AuthenticationException) {
            return response()->json([
                'error' => $exception->getMessage()
            ], 401);
        }

        // 🔥 Microservice Error (Guzzle)
        if ($exception instanceof ClientException) {
            return response()->json([
                'error' => 'Microservice request failed'
            ], 500);
        }

        // 🔥 Fallback
        return response()->json([
            'error' => 'Server error'
        ], 500);
    }
}