<?php

namespace App\Services\Api\v1\Auth;


use App\DTOs\Api\v1\Auth\TokensDTO;
use App\DTOs\Api\v1\Auth\UserAuthDTO;
use App\Models\PersonalRefreshToken;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\Api\v1\SanctumRefreshTokens\TokenService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Hash;

final readonly class AuthService
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

    public function auth(UserAuthDTO $dto): ?TokensDTO
    {
        $user = $this->userRepository->findByEmail($dto->email);
        if ($user) {
            return null;
        }

        $user = User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
            'gender' => $dto->gender,
        ]);

        return new TokenService($user)
            ->createTokens();
    }

    public function refresh(string $refreshToken): TokensDTO
    {
        $personalRefreshToken = PersonalRefreshToken::findToken($refreshToken);

        if (!$personalRefreshToken?->tokenable) {
            throw new AuthenticationException;
        }

        $tokenDTO = new TokenService($personalRefreshToken->tokenable)
            ->createTokens();

        event(new Login('sanctum', $personalRefreshToken->tokenable, false));

        $personalRefreshToken->delete();

        return $tokenDTO;
    }
}
