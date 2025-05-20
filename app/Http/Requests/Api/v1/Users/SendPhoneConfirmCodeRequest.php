<?php

namespace App\Http\Requests\Api\v1\Users;

use App\Http\Requests\Concerns\HasTranslatedAttribute;
use App\Rules\IsPhoneValidRule;
use Illuminate\Foundation\Http\FormRequest;

final class SendPhoneConfirmCodeRequest extends FormRequest
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
            'phone' => ['required', 'string', 'max:255', new IsPhoneValidRule, 'unique:users,phone'],
        ];
    }

    public function getData(): string
    {
        return $this->validated('phone');
    }
}
