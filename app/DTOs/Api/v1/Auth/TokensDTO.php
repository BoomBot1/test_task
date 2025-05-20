<?php

namespace App\DTOs\Api\v1\Auth;

use App\Models\Contracts\HasApiTokens;
use Illuminate\Support\Carbon;

final readonly class TokensDTO
{
    public function __construct(
        public string $model,
        public string $tokenType,
        public string $accessToken,
        public string $refreshToken,
        public Carbon $accessTokenExpiresAt,
        public Carbon $refreshTokenExpiresAt,
        public HasApiTokens $tokenable,
    ) {
    }
}
