<?php

namespace App\Http\Resources\Api\v1\Auth;

use App\DTOs\Api\v1\Auth\TokensDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property TokensDTO $resource
 */
final class TokensResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            /**
             * Access токен
             *
             * @var string
             *
             * @example 1|fTr7HaqqFN7kheTPpNQslloeNXXrZwWXEE35NAtJbb1b26b9
             */
            'accessToken' => $this->resource->accessToken,

            /**
             * Refresh токен
             *
             * @var string
             *
             * @example 1|fTr7HaqqFN7kheTP12slloe5XXrZwWXFE35NAtJbb1b26b9
             */
            'refreshToken' => $this->resource->refreshToken,

            /**
             * @var int
             *
             * @example 1745152417
             */
            'accessTokenExpiresAt' => $this->resource->accessTokenExpiresAt->timestamp,

            /**
             * @var int
             *
             * @example 1745152417
             */
            'refreshTokenExpiresAt' => $this->resource->refreshTokenExpiresAt->timestamp,
        ];
    }
}
