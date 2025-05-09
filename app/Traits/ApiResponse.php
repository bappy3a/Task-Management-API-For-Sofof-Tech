<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    public function ResponseSuccess(
        $data,
        $metadata = null,
        $message = 'Successfully!',
        $status_code = 200,
        $status = true
    ): JsonResponse
    {
        return response()->json([
            'success' => $status,
            'status_code' => $status_code,
            'status' => $status_code,
            'message' => $message,
            'data' => $data,
            'metadata' => $metadata,
        ], $status_code);
    }

    public function ResponseError(
        $errors,
        $metadata = null,
        $message = 'Data Process Error!',
        $status_code = 400,
        $status = false
    ): JsonResponse
    {
        return response()->json([
            'success' => $status,
            'status_code' => $status_code,
            'status' => $status_code,
            'message' => $message,
            'errors' => $errors,
            'metadata' => $metadata,
        ], $status_code);
    }
}
