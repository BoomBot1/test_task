<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Auth\RefreshRequest;
use App\Http\Requests\Api\v1\Auth\UserAuthRequest;
use App\Http\Resources\Api\v1\Auth\TokensResource;
use App\Services\Api\v1\Auth\AuthService;
use Illuminate\Http\JsonResponse;

final class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $service
    ) {
    }

    /**
     * Аутентификация
     *
     * @response {
     *     message: string,
     *     data: array{
     *          'token': TokensResource
     *      }
     *  }
     *
     * @unauthenticated
     */
    public function register(UserAuthRequest $request): JsonResponse
    {
        $requestData = $request->getData();
        $tokensDTO = $this->service->auth($requestData);

        return $this->successResponse(
            data: [
                'token' => TokensResource::make($tokensDTO),
            ],
        );
    }

    /**
     * Обновление токенов
     *
     * @response array{
     *     message: string,
     *     data: {
     *         token: TokenResource
     *     }
     * }
     *
     * @unauthenticated
     */
    public function refresh(RefreshRequest $request)
    {
        return $this->successResponse(
            data: [
                'token' => TokensResource::make(
                    $this
                        ->service
                        ->refresh($request->getData()),
                ),
            ]
        );
    }
}
