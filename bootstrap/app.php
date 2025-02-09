<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->expectsJson()) {
                $statusCode = match (true) {
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

                $errorCode = match (true) {
                    $e instanceof AuthenticationException => 'unauthenticated',
                    $e instanceof AuthorizationException => 'unauthorized',
                    $e instanceof ModelNotFoundException => 'model_not_found',
                    $e instanceof NotFoundHttpException => 'endpoint_not_found',
                    $e instanceof MethodNotAllowedHttpException => 'method_not_allowed',
                    $e instanceof ThrottleRequestsException => 'too_many_requests',
                    $e instanceof ValidationException => 'validation_failed',
                    default => 'server_error'
                };

                $message = config('app.debug')
                    ? $e->getMessage()
                    : match (get_class($e)) {
                        ValidationException::class => 'The provided data does not meet our validation requirements',
                        ModelNotFoundException::class => 'The requested resource could not be found in our system',
                        AuthenticationException::class => 'Please sign in to access this resource',
                        AuthorizationException::class => 'You do not have permission to perform this action',
                        NotFoundHttpException::class => 'The requested URL endpoint was not found on our server',
                        MethodNotAllowedHttpException::class => 'This type of request is not supported for this endpoint',
                        ThrottleRequestsException::class => 'Request limit exceeded. Please try again later',
                        default => 'An internal system error occurred. Our team has been notified'
                    };

                $response = [
                    'code' => $errorCode,
                    'message' => $message,
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

            return null;
        });
    })->create();
