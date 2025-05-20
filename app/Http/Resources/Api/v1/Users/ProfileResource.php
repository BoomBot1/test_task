<?php

namespace App\Http\Resources\Api\v1\Users;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property User $resource
 */
final class ProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            /**
             * Имя
             *
             * @var string|null
             *
             * @example Иванов Иван Иванович
             */
            'name' => $this->resource->name,

            /**
             * Email
             *
             * @var string
             *
             * @example exmaple@g.com
             */
            'email' => $this->resource->email,

            /**
             * Гендер
             *
             * @var string
             *
             * @example male
             */
            'gender' => $this->resource->gender,
        ];
    }
}
