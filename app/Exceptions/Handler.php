<?php

namespace App\Exceptions;

use Illuminate\Validation\ValidationException;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    protected function invalidJson($request, ValidationException $exception)
    {
        return response()->json([
            'success' => false,
            'message' => $exception->getMessage(),
            'errors' => $exception->errors(),
            'data' => null,
        ], $exception->status);
    }
}
