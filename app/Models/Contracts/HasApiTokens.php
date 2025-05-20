<?php

namespace App\Models\Contracts;

use App\Models\PersonalRefreshToken;
use App\Support\SanctumRefreshTokens\NewRefreshToken;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface HasApiTokens extends \Laravel\Sanctum\Contracts\HasApiTokens
{
    public function refreshTokens(): MorphMany;

    public function createRefreshToken(
        ?DateTimeInterface $expiresAt = null,
        ?int $accessTokenId = null
    ): NewRefreshToken;

    public function withRefreshToken(?PersonalRefreshToken $refreshToken): static;
}
