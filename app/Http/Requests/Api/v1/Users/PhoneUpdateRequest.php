<?php

namespace App\Http\Requests\Api\v1\Users;

use App\Http\Requests\Concerns\HasTranslatedAttribute;
use App\Rules\IsPhoneChangeCodeValidRule;
use App\Rules\IsPhoneValidRule;
use Illuminate\Foundation\Http\FormRequest;

final class PhoneUpdateRequest extends FormRequest
{
    use HasTranslatedAttribute;

    public function rules(): array
    {
        return [
            /**
             * Телефон
             *
             * @example +79605553535
             */
            'phone' => ['required', 'string', 'max:255', new IsPhoneValidRule],

            /**
             * Код подтверждение
             *
             * @example 123456
             */
            'code' => [
                'required',
                'string',
                'min:6',
                'max:6',
                new IsPhoneChangeCodeValidRule,
            ],
        ];
    }

    public function getData(): string
    {
        return $this->validated('phone');
    }
}
