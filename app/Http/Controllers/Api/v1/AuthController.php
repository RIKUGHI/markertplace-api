<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\RoleEnum;
use App\Helper\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;

class AuthController extends Controller
{
    public function registration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
            'role' => ['required', new Enum(RoleEnum::class)]
        ]);

        if ($validator->fails()) {
            return Api::sendResponse(401, $validator->errors(), null);
        }

        if (User::query()->where('email', $request->email)->exists()) {
            return Api::sendResponse(409, "email already exists", null);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        return Api::sendResponse(200, "account created", UserResource::make($user));
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if ($validator->fails()) {
            return Api::sendResponse(401, $validator->errors(), null);
        }

        if (!Auth::attempt($request->only(['email', 'password']))) {
            return Api::sendResponse(409, "The provided credentials do not match our records", null);
        }

        $user = User::query()->where('email', $request->email)->first();

        return Api::sendResponse(200, "", [
            'name' => $user->name,
            'email' => $user->email,
            "token" => $user->createToken('marketplace-api', [$user->role->value])->plainTextToken
        ]);
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();

        return Api::sendResponse(200, "Logout", null);
    }
}
