<?php

namespace App\Providers;

use App\Filament\Support\Mixins\ComponentMixin;
use App\Support\Mixin\CarbonMixin;
use Filament\Support\Components\Component;
use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Traits\Macroable;
use ReflectionException;

final class MixinServiceProvider extends ServiceProvider
{
    protected array $mixins = [
        Carbon::class => CarbonMixin::class,
        Component::class => ComponentMixin::class,
    ];

    /**
     * @throws ReflectionException
     */
    public function boot(): void
    {
        foreach ($this->mixins as $class => $mixin) {
            /** @var class-string<Macroable> $class */
            $class::mixin(new $mixin);
        }
    }
}
