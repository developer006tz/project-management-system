<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ValidateRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (! $request->user() || ! in_array($request->user()->role, $roles)) {
            throw new AccessDeniedHttpException('You do not have permission to perform this action');
        }

        return $next($request);
    }
}
