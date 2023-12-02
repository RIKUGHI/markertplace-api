<?php 

namespace App\DTO;

use App\Http\Requests\LoginRequest;

class LoginDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {

    }

    public static function fromApiRequest(LoginRequest $request)
    {
        return new self(
            email: $request->validated('email'),
            password: $request->validated('password'),
        );
    }
}