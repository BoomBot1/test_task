<?php

namespace App\Filament\Support\PageConfigs;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

/**
 * @mixin ViewRecord
 *
 * @noinspection PhpUnused
 */
trait HasViewPageConfig
{
    public function getTitle(): string
    {
        return $this->getResource()::transFor('label.view');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
