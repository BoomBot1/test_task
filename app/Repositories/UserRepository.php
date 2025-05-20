<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

final readonly class UserRepository
{
    public function baseQuery(): Builder
    {
        return User::query();
    }

    public function findByEmail($userEmail): ?User
    {
        return $this
            ->baseQuery()
            ->where('email', $userEmail)
            ->first();
    }
}
