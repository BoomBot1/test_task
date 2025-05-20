<?php

namespace App\Http\Controllers\Api\v1\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\Users\ProfileResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ProfileController extends Controller
{
    /**
     * Профиль пользователя
     *
     * @response array{
     *     message: string,
     *     data: ProfileResource
     * }
     */
    public function show(Request $request): JsonResponse
    {
        return $this->successResponse(
            data: ProfileResource::make($request->user()),
        );
    }
}
