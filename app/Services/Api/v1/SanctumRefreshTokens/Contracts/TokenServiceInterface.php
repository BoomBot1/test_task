<?php

namespace App\Services\Api\v1\SanctumRefreshTokens\Contracts;

use App\DTOs\Api\v1\Auth\TokensDTO;

interface TokenServiceInterface
{
    public function createTokens(): TokensDTO;

    public function deleteCurrentTokens(): void;
}
