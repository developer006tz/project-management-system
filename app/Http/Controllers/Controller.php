<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function successResponse($data, ?string $message = null, int $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message ?? 'Completed successfully',
            'data' => $data,
        ], $code);
    }

    protected function errorResponse(string $message, int $code)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message ?? 'An error occurred',
        ], $code);
    }
}
