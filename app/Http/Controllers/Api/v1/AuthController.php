<?php

namespace App\Http\Controllers\Api\v1;

use App\DTO\LoginDTO;
use App\DTO\RegistrationDTO;
use App\Enums\RoleEnum;
use App\Helper\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {
    }

    public function registration(RegistrationRequest $request)
    {
        try {
            return $this->authService->registration(RegistrationDTO::fromApiRequest($request));
        } catch (\Throwable $th) {
            if ($th instanceof HttpResponseException) return $th->getResponse();

            return Api::sendResponse(JsonResponse::HTTP_INTERNAL_SERVER_ERROR, "Internal Server Error", null);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            return $this->authService->login(LoginDTO::fromApiRequest($request));
        } catch (\Throwable $th) {
            if ($th instanceof HttpResponseException) return $th->getResponse();

            return Api::sendResponse(JsonResponse::HTTP_INTERNAL_SERVER_ERROR, "Internal Server Error", null);
        }
    }

    public function logout()
    {
        $this->authService->logout();

        return Api::sendResponse(200, "Logout", null);
    }
}
