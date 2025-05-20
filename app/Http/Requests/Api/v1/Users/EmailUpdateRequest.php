<?php

namespace App\Http\Requests\Api\v1\Users;

use App\Http\Requests\Concerns\HasTranslatedAttribute;
use App\Rules\IsEmailChangeCodeValidRule;
use Illuminate\Foundation\Http\FormRequest;

final class EmailUpdateRequest extends FormRequest
{
    use HasTranslatedAttribute;

    public function rules(): array
    {
        return [
            /**
             * Email
             *
             * @example example@cyberia.studio
             */
            'email' => ['required', 'string', 'email', 'max:255'],

            /**
             * Код подтверждения
             *
             * @example 123456
             */
            'code' => [
                'required',
                'string',
                'min:6',
                'max:6',
                new IsEmailChangeCodeValidRule,
            ],
        ];
    }

    public function getData(): string
    {
        return $this->validated('email');
    }
}
