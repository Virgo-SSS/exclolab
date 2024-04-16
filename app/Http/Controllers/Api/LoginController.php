<?php

namespace App\Http\Controllers\Api;

use App\Actions\LoginAction;
use App\Http\Requests\Api\LoginRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\Auth;

class LoginController extends ApiController
{
    /**
     * Function to handle user login request
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function index(LoginRequest $request, LoginAction $action): JsonResponse
    {
        try {
            $isSuccess = $action->handle($request->validated());

            if(!$isSuccess) {
                return $this->error(null, "Invalid Username Or Password !!");
            }

            $user = User::query()->where('email', $request->email)->first();

            if(!$user->hasVerifiedEmail()) {
                return $this->error([
                    'link' => 'https://www.google.com/' // the example of verification link
                ], "Can't Login Must Verify Email First", "403");
            }

            $data = [
                'user' => $user,
                'token' => $user->createToken('basic-token')->plainTextToken,
            ];

            Auth::logoutOtherDevices($request->password);

            return $this->success($data, "Successfully Login");
        }catch (\Throwable $th) {
            $this->logError($th, [
                'request' => $request->all(),
                'header' => $request->header()
            ], $th->getMessage());

            return $this->error(null, "Failed Login", $th->getCode());
        }

    }

    public function logout(Request $request): JsonResponse
    {
        try {
            Auth::user()->tokens()->delete();

            return $this->success(null, "Success Logout");
        } catch (\Throwable $th) {
            $this->logError($th, [
                'request' => $request->all(),
                'header' => $request->header()
            ], $th->getMessage());

            return $this->error(null, "Failed Login", $th->getCode());
        }
    }
}
