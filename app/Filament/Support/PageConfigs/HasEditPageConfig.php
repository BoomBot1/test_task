<?php

namespace App\Filament\Support\PageConfigs;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/**
 * @mixin EditRecord
 *
 * @noinspection PhpUnused
 */
trait HasEditPageConfig
{
    public function getTitle(): string
    {
        return $this->getResource()::transFor('label.edit');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->modalHeading(self::$resource::transFor('label.delete')),
        ];
    }
}
