<?php

namespace App\Filament\Support\Navigation;

use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;

/**
 * @mixin Resource
 *
 * @noinspection PhpUnused
 */
trait HasResourceNavigationConfig
{
    public static function getNavigationSort(): ?int
    {
        return NavigationConfig::navigationSort(static::class);
    }

    public static function getNavigationGroup(): ?string
    {
        return NavigationConfig::navigationGroup(static::class);
    }

    public static function getNavigationIcon(): string
    {
        return NavigationConfig::navigationIcon(static::class);
    }

    public static function getRecordTitleAttribute(): ?string
    {
        return NavigationConfig::recordTitleAttribute(static::class);
    }

    public static function getCluster(): ?string
    {
        return NavigationConfig::cluster(static::class);
    }

    public static function getSubNavigationPosition(): SubNavigationPosition
    {
        return NavigationConfig::subNavigationPosition(static::class);
    }
}
