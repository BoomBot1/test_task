<?php

namespace App\Observers;

use App\Models\PersonalRefreshToken;

final class RefreshTokenObserver
{
    public function deleting(PersonalRefreshToken $refreshToken): void
    {
        if ($refreshToken->accessToken) {
            $refreshToken->accessToken->delete();
        }
    }
}
