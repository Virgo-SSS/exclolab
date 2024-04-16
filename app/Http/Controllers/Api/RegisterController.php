<?php

namespace App\Http\Controllers\Api;

use App\Actions\RegisterAction;
use App\Http\Requests\Api\RegisterRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Api\ApiController;

class RegisterController extends ApiController
{
    /**
     * Function to handle user reqister request
     *
     * @param RegisterRequest $request
     * @param RegisterAction $action
     * @return JsonResponse
     */
    public function index(RegisterRequest $request, RegisterAction $action): JsonResponse
    {
        try {
            $action->handle($request->validated());

            return $this->success(null, "Successfully Register");
        } catch(\Throwable $th) {
            $this->logError($th, [
                'request' => $request->all(),
                'header' => $request->header()
            ], $th->getMessage());

            return $this->error(null, "Failed Register", $th->getCode());
        }
    }
}
