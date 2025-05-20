<?php

namespace App\ValueObjects;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;
use Stringable;

abstract class BaseValueObject implements Arrayable, Castable, Jsonable, JsonSerializable, Stringable
{
    public function __toString(): string
    {
        return $this->toJson();
    }

    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), flags: $options);
    }

    public function jsonSerialize(): string
    {
        return $this->toJson();
    }

    abstract public static function castUsing(array $arguments): string;

    abstract public static function fromArray(array $data): static;

    abstract public function toArray(): array;
}
