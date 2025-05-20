<?php

namespace App\Filament\Support\Translation;

trait HasLang
{
    public static function getLang(): string
    {
        return app()->getLocale();
    }
}
