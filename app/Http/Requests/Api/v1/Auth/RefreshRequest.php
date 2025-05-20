<?php

namespace App\Http\Requests\Api\v1\Auth;

use App\Http\Requests\Concerns\HasTranslatedAttribute;
use Illuminate\Foundation\Http\FormRequest;

final class RefreshRequest extends FormRequest
{
    use HasTranslatedAttribute;

    public function rules(): array
    {
        return [
            /**
             * Refresh token
             *
             * @example 100|fTr7HaqqFN7kheTPpNQslloeNXXradfjasASldaldkjB
             */
            'refreshToken' => ['required', 'string'],
        ];
    }

    public function getData(): string
    {
        return $this->validated('refreshToken');
    }
}
