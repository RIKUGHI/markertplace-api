<?php 

namespace App\DTO;

use App\Http\Requests\RegistrationRequest;

class RegistrationDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly string $role,
    ) {

    }

    public static function fromApiRequest(RegistrationRequest $request)
    {
        return new self(
            name: $request->validated('name'),
            email: $request->validated('email'),
            password: $request->validated('password'),
            role: $request->validated('role'),
        );
    }
}