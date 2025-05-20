<?php

namespace App\Enums\Filament;

use Filament\Support\Contracts\HasLabel;

enum FilamentNavigationGroup: string implements HasLabel
{
    case System = 'system';

    public function getLabel(): ?string
    {
        return __("enums/filament/navigation-group.$this->value");
    }
}
