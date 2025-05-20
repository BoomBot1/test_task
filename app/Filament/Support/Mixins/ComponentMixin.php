<?php

namespace App\Filament\Support\Mixins;

use Closure;
use Filament\Forms\Components\Concerns\HasPlaceholder;
use Filament\Support\Components\Component;
use Illuminate\Support\Str;

/**
 * @mixin Component
 */
final class ComponentMixin
{
    public function translate(): Closure
    {
        return function (string $key, bool $snake = false) {
            if (method_exists($this, 'getName')) {
                $name = $this->getName();

                if ($snake) {
                    $name = Str::replace('.', '_', $name);
                }

                if (method_exists($this, 'label')) {
                    $this->label(__("$key.common.$name"));
                }

                if (in_array(HasPlaceholder::class, class_uses_recursive($this))) {
                    $this->placeholder(__("$key.placeholder.$name"));
                }
            }

            return $this;
        };
    }
}
