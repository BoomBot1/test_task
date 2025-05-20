<?php

namespace App\Support\SanctumRefreshTokens;

use App\Models\PersonalRefreshToken;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Laravel\Sanctum\PersonalAccessToken;

final class NewRefreshToken implements Arrayable, Jsonable
{
    public PersonalRefreshToken $refreshToken;

    /**
     * The plain text version of the token.
     */
    public string $plainTextToken;

    /**
     * Create a new access token result.
     *
     * @param PersonalAccessToken $accessToken
     *
     * @return void
     */
    public function __construct(PersonalRefreshToken $accessToken, string $plainTextToken)
    {
        $this->refreshToken = $accessToken;
        $this->plainTextToken = $plainTextToken;
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        return [
            'refreshToken' => $this->refreshToken,
            'plainTextToken' => $this->plainTextToken,
        ];
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param int $options
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }
}
