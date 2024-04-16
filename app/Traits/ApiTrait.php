<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait ApiTrait
{
    /**
     * function to Return json success response
     *
     * @param mixed $data
     * @param string $message
     * @param string $code
     * @return JsonResponse
     */
    public function success(mixed $data, string $message = "", string $code = "200"): JsonResponse
    {
        return response()->json([
            "status" => "Success",
            'code' => $code,
            'message' => $message,
            "data" => $data
        ]);
    }

    /**
     * function to Return json error response
     *
     * @param mixed $data
     * @param string $message
     * @param string $code
     * @return JsonResponse
     */
    public function error(mixed $data, string $message = "", string $code = "500"): JsonResponse
    {
        return response()->json([
            "status" => "Error",
            'code' => $code,
            'message' => $message,
            "data" => $data
        ]);
    }

    /**
     * Function to logging api error
     *
     * @param \Throwable $th
     * @param array|null $data
     * @param string $message
     * @return void
     */
    public function logError(\Throwable $th, ?array $data, string $message = "Error",): void
    {
        Log::channel('api')->error(
            'Message : ' . $message . PHP_EOL .
            'Stack Trace : ' . $th->getTraceAsString() . PHP_EOL .
            'Data : ' . json_encode($data) ?? null .  PHP_EOL
        );
    }
}
