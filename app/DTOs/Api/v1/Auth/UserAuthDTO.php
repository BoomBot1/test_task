<?php

namespace App\DTOs\Api\v1\Auth;

use App\Enums\User\GenderType;

final readonly class UserAuthDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly GenderType $gender,
    ) {
    }
}
