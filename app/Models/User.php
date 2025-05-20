<?php

namespace App\Models;

use App\Enums\User\GenderType;
use Database\Factories\UserFactory;
use Eloquent;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 *
 * @property string|null $name
 * @property GenderType $gender
 * @property string $email
 * @property string $password
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read string|null $full_name
 *
 * @method static UserFactory factory($count = null, $state = [])
 * @method static Builder<static>|User query()
 *
 * @mixin Eloquent
 */
final class User extends Authenticatable implements AuthorizableContract, Contracts\HasApiTokens
{
    use Authorizable,
        Concerns\HasApiTokens,
        HasFactory;

    protected $table = 'users';

    protected $guarded = ['id'];

    protected $casts = [
        'gender' => GenderType::class,
    ];
}
