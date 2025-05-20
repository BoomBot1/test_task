<?php

namespace App\Filament\Support\PageConfigs;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/**
 * @mixin ListRecords
 *
 * @noinspection PhpUnused
 */
trait HasListPageConfig
{
    public function getTitle(): string
    {
        return $this->getResource()::transFor('label.main');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalHeading(self::$resource::transFor('label.create')),
        ];
    }
}
