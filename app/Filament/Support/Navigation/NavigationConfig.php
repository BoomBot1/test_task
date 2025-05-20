<?php

namespace App\Filament\Support\Navigation;

use App\Enums\Filament\FilamentNavigationGroup;
use App\Filament\Resources\BriefResource;
use App\Filament\Resources\FeedbackResource;
use App\Filament\Resources\NewsResource;
use App\Filament\Resources\Report\ReportResource;
use App\Filament\Resources\Report\ReportTypeResource;
use App\Filament\Resources\System\AdminResource;
use App\Filament\Resources\TransactionResource;
use App\Filament\Resources\UserResource;
use Filament\Pages\SubNavigationPosition;

final class NavigationConfig
{
    public static function navigationSort(string $class): ?int
    {
        return match ($class) {
            AdminResource::class => 1,

            ReportResource::class => 1,
            ReportTypeResource::class => 2,

            default => null,
        };
    }

    public static function navigationGroup(string $class): ?string
    {
        return match ($class) {
            AdminResource::class => FilamentNavigationGroup::System->getLabel(),

            default => null,
        };
    }

    public static function navigationIcon(string $class): ?string
    {
        return match ($class) {
            AdminResource::class => 'heroicon-o-user-circle',
            UserResource::class => 'heroicon-o-users',
            FeedbackResource::class => 'heroicon-o-list-bullet',
            BriefResource::class => 'heroicon-o-document',
            NewsResource::class => 'heroicon-o-newspaper',
            TransactionResource::class => 'heroicon-o-arrows-up-down',

            default => 'heroicon-c-bars-4',
        };
    }

    public static function recordTitleAttribute(string $class): ?string
    {
        return match ($class) {
            AdminResource::class => 'name',

            default => null,
        };
    }

    public static function cluster(string $class): ?string
    {
        return null;
    }

    public static function subNavigationPosition(string $class): SubNavigationPosition
    {
        return SubNavigationPosition::Top;
    }
}
