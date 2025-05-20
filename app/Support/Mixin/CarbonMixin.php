<?php

namespace App\Support\Mixin;

use Closure;
use Illuminate\Support\Carbon;

final class CarbonMixin
{
    public function parseWithTimestamp(): Closure
    {
        return static function (mixed $date = null): ?Carbon {
            if (is_null($date)) {
                return null;
            }

            return is_numeric($date)
                ? Carbon::createFromTimestamp($date)
                : Carbon::parse($date);
        };
    }
}
