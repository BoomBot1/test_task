<?php

namespace App\Http\Requests\Api\v1\Auth;

use App\DTOs\Api\v1\Auth\UserAuthDTO;
use App\Enums\User\GenderType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UserAuthRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            /**
             * Имя
             *
             * @example Иванов Иван Иванович
             */
            'name' => ['required', 'string', 'max:255'],
            /**
             * Email
             *
             * @example example@g.com
             */
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],

            /**
             * Пароль
             *
             * @example dfxghnjkldsfjn
             */
            'password' => [
                'required',
                'string',
                'min:6',
                'max:18',
            ],

            /**
             * Гендер
             *
             * @example male
             */
            'gender' => ['required', 'string', Rule::enum(GenderType::class)],
        ];
    }

    public function getData(): UserAuthDTO
    {
        return new UserAuthDTO(
            name: $this->validated('name'),
            email: $this->validated('email'),
            password: $this->validated('password'),
            gender: GenderType::from($this->validated('gender')),
        );
    }
}
