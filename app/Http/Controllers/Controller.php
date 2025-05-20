<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    public function successResponse(
        ?string $message = null,
        Responsable|array|null $data = []
    ): JsonResponse {
        return response()
            ->json([
                /** @example Успех */
                'message' => is_null($message)
                    ? __('api.base.success')
                    : $message,

                'data' => $data,
            ]);
    }
}
