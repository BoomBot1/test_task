<?php

namespace App\Services\Api\v1\Users;

use App\DTOs\Api\v1\Users\ProfileUpdateRequestDTO;
use App\Enums\Auth\CodeRecipientType;
use App\Enums\VerificationCode\CodePurposeType;
use App\Models\User;
use App\Services\Api\v1\Auth\VerificationCodeService;

final readonly class UserService
{
    public function updateProfile(User $user, ProfileUpdateRequestDTO $newData): User
    {
        $user->update([
            'first_name' => $newData->firstName,
            'last_name' => $newData->lastName,
            'middle_name' => $newData->middleName,
        ]);

        return $user;
    }

    public function sendVerificationCode(
        string $phone,
        CodeRecipientType $recipientType,
        CodePurposeType $purposeType
    ): void {
        app(VerificationCodeService::class)
            ->sendCode(
                payload: $phone,
                recipientType: $recipientType,
                purposeType: $purposeType,
            );
    }

    public function updatePhone(User $user, string $phone): User
    {
        $user->update(['phone' => $phone]);
        app(VerificationCodeService::class)
            ->markCodeAsUsed(
                $phone,
                CodeRecipientType::Phone,
                CodePurposeType::PhoneChange,
            );

        return $user;
    }

    public function updateEmail(User $user, string $email): User
    {
        $user->update(['email' => $email]);
        app(VerificationCodeService::class)
            ->markCodeAsUsed(
                $email,
                CodeRecipientType::Email,
                CodePurposeType::EmailChange,
            );

        return $user;
    }
}
