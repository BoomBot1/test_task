<?php

namespace App\Exceptions;

final class VerificationCodeException extends BusinessLogicException
{
    protected function getDefaultMessage(): string
    {
        return __('api.exception.verification_code_error');
    }
}
