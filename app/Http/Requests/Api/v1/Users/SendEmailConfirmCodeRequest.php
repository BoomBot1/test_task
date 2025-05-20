<?php

namespace App\Http\Requests\Api\v1\Users;

use App\Http\Requests\Concerns\HasTranslatedAttribute;
use Illuminate\Foundation\Http\FormRequest;

final class SendEmailConfirmCodeRequest extends FormRequest
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
            'email' => ['required', 'string', 'max:255', 'email', 'unique:users,email'],
        ];
    }

    public function getData(): string
    {
        return $this->validated('email');
    }
}
