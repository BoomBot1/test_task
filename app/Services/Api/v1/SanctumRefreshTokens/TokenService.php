<?php

namespace App\Services\Api\v1\SanctumRefreshTokens;

use App\DTOs\Api\v1\Auth\TokensDTO;
use App\Enums\SanctumRefreshToken\TokenType;
use App\Models\Contracts\HasApiTokens;
use App\Models\PersonalRefreshToken;
use App\Support\SanctumRefreshTokens\NewRefreshToken;
use Laravel\Sanctum\NewAccessToken;

final class TokenService implements Contracts\TokenServiceInterface
{
    protected NewAccessToken $token;

    protected NewRefreshToken $refreshToken;

    public function __construct(protected HasApiTokens $tokenable)
    {
    }

    public function createTokens(array $abilities = ['*']): TokensDTO
    {
        [$accessTokenExpiresAt, $refreshTokenExpiresAt] = $this->getExpiresAt();

        $this->token = $this->tokenable->createToken(
            TokenType::AccessToken->value,
            $abilities,
            $accessTokenExpiresAt,
        );

        $this->refreshToken = $this->tokenable->createRefreshToken(
            $refreshTokenExpiresAt,
            $this->token->accessToken?->id,
        );

        return new TokensDTO(
            model: $this->tokenable->getMorphClass(),
            tokenType: 'Bearer',
            accessToken: $this->token->plainTextToken,
            refreshToken: $this->refreshToken->plainTextToken,
            accessTokenExpiresAt: $accessTokenExpiresAt,
            refreshTokenExpiresAt: $refreshTokenExpiresAt,
            tokenable: $this->tokenable,
        );
    }

    public function deleteCurrentTokens(): void
    {
        if ($this->tokenable->currentAccessToken()) {
            PersonalRefreshToken::query()
                ->where('access_token_id', $this->tokenable->currentAccessToken()->id)
                ->delete();
            $this->tokenable->currentAccessToken()->delete();
        }
    }

    protected function getExpiresAt(): array
    {
        return [
            now()->addMinutes(config('sanctum.expiration', 1440)),
            now()->addMinutes(config('sanctum.refresh_token_expiration', 43200)),
        ];
    }
}
