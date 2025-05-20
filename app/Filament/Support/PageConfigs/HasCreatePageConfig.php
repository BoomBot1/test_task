<?php

namespace App\Filament\Support\PageConfigs;

use Filament\Resources\Pages\CreateRecord;

/**
 * @mixin CreateRecord
 *
 * @noinspection PhpUnused
 */
trait HasCreatePageConfig
{
    public function getTitle(): string
    {
        return $this->getResource()::transFor('label.create');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
