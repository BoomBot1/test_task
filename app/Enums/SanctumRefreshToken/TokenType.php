<?php

namespace App\Enums\SanctumRefreshToken;

enum TokenType: string
{
    case AccessToken = 'access-token';
    case RefreshToken = 'refresh-token';
}
