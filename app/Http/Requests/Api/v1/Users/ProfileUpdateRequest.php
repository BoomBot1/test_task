<?php

namespace App\Http\Requests\Api\v1\Users;

use App\DTOs\Api\v1\Users\ProfileUpdateRequestDTO;
use App\Http\Requests\Concerns\HasTranslatedAttribute;
use Illuminate\Foundation\Http\FormRequest;

final class ProfileUpdateRequest extends FormRequest
{
    use HasTranslatedAttribute;

    public function rules(): array
    {
        return [
            /**
             * Имя
             *
             * @example Иван
             */
            'firstName' => ['nullable', 'string', 'max:255'],

            /**
             * Фамилия
             *
             * @example Иванов
             */
            'lastName' => ['nullable', 'string', 'max:255'],

            /**
             * Отчество
             *
             * @example Иванович
             */
            'middleName' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function getData(): ProfileUpdateRequestDTO
    {
        return new ProfileUpdateRequestDTO(
            firstName: $this->validated('firstName'),
            lastName: $this->validated('lastName'),
            middleName: $this->validated('middleName'),
        );
    }
}
