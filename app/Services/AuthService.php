<?php 

namespace App\Services;

use App\DTO\LoginDTO;
use App\DTO\RegistrationDTO;
use App\Helper\Api;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService {
  public function registration(RegistrationDTO $dto) {
    if (User::query()->where('email', $dto->email)->exists()) {
      throw new HttpResponseException(
        Api::sendResponse(JsonResponse::HTTP_CONFLICT, "Email already exists", null)
      );
    }

    $user = User::create([
        'name' => $dto->name,
        'email' => $dto->email,
        'password' => Hash::make($dto->password),
        'role' => $dto->role
    ]);

    return Api::sendResponse(JsonResponse::HTTP_OK, "account created", UserResource::make($user));
  }

  public function login(LoginDTO $dto) {
    if (!Auth::attempt(['email' => $dto->email, 'password' => $dto->password])) {
      throw new HttpResponseException(
        Api::sendResponse(JsonResponse::HTTP_CONFLICT, "The provided credentials do not match our records", null)
      );
    }

    $user = User::query()->where('email', $dto->email)->first();

    return Api::sendResponse(200, "", [
      'name' => $user->name,
      'email' => $user->email,
      "token" => $user->createToken('marketplace-api', [$user->role->value])->plainTextToken
    ]); 
  }

  public function logout() {
    Auth::user()->tokens()->delete();
  }
}