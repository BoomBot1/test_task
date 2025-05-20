<?php

namespace App\Models\Concerns;

trait HasApiTokens
{
    use HasRefreshTokens,
        \Laravel\Sanctum\HasApiTokens;
}
