<?php

namespace App\Helper;

use Illuminate\Http\JsonResponse;

class ResponseHelper
{
    public static function Out(string $message, $data = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'status' => $code
        ], $code);
    }
}