<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class GlobalException extends Handler
{
    public function render($request, Throwable $e)
    {
        if ($request->expectsJson()) {
            return $this->handleJsonException($e);
        }

        return parent::render($request, $e);
    }

    protected function handleJsonException(Throwable $e)
    {
        $statusCode = $this->getStatusCode($e);
        $response = [
            'code' => $this->getErrorCode($e),
            'message' => $this->getErrorMessage($e),
        ];

        if ($e instanceof ValidationException) {
            $response['errors'] = $e->errors();
        }

        if (config('app.debug')) {
            $response['debug'] = [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTrace(),
            ];
        }

        return response()->json($response, $statusCode);
    }

    protected function getStatusCode(Throwable $e): int
    {
        return match (true) {
            $e instanceof AuthenticationException => 401,
            $e instanceof AuthorizationException => 403,
            $e instanceof ModelNotFoundException,
            $e instanceof NotFoundHttpException => 404,
            $e instanceof MethodNotAllowedHttpException => 405,
            $e instanceof ThrottleRequestsException => 429,
            $e instanceof ValidationException => 422,
            method_exists($e, 'getStatusCode') => $e->getStatusCode(),
            default => 500
        };
    }

    protected function getErrorCode(Throwable $e): string
    {
        return match (true) {
            $e instanceof AuthenticationException => 'unauthenticated',
            $e instanceof AuthorizationException => 'unauthorized',
            $e instanceof ModelNotFoundException => 'model_not_found',
            $e instanceof NotFoundHttpException => 'endpoint_not_found',
            $e instanceof MethodNotAllowedHttpException => 'method_not_allowed',
            $e instanceof ThrottleRequestsException => 'too_many_requests',
            $e instanceof ValidationException => 'validation_failed',
            default => 'server_error'
        };
    }

    protected function getErrorMessage(Throwable $e): string
    {
        if (config('app.debug')) {
            return $e->getMessage();
        }

        return match (get_class($e)) {
            ValidationException::class => 'Invalid input data',
            ModelNotFoundException::class => 'Resource not found',
            AuthenticationException::class => 'Unauthenticated',
            AuthorizationException::class => 'Unauthorized',
            NotFoundHttpException::class => 'Endpoint not found',
            MethodNotAllowedHttpException::class => 'Method not allowed',
            ThrottleRequestsException::class => 'Too many requests',
            default => 'An unexpected error occurred'
        };
    }
}
