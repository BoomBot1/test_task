<?php

namespace App\Filament\Support\Navigation;

use Filament\Pages\Page;
use Filament\Pages\SubNavigationPosition;

/**
 * @mixin Page
 *
 * @noinspection PhpUnused
 */
trait HasPageNavigationConfig
{
    public static function getNavigationSort(): ?int
    {
        return NavigationConfig::navigationSort(static::class);
    }

    public static function getNavigationIcon(): ?string
    {
        return NavigationConfig::navigationIcon(static::class);
    }

    public static function getCluster(): ?string
    {
        return NavigationConfig::cluster(static::class);
    }

    public function getSubNavigationPosition(): SubNavigationPosition
    {
        return NavigationConfig::subNavigationPosition(static::class);
    }
}
