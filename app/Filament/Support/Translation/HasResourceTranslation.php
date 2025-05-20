<?php

namespace App\Filament\Support\Translation;

use Filament\Resources\Resource;

/**
 * @mixin Resource
 *
 * @noinspection PhpUnused
 */
trait HasResourceTranslation
{
    use HasLang;

    abstract public static function getTranslateModelName(): string;

    public static function trans(
        array $items,
        string $additional = '',
        bool $doSnake = false,
    ): array {
        $model = static::getTranslateModelName() . $additional;

        return collect($items)
            ->each(static function ($item) use ($model, $doSnake) {
                if (method_exists($item, 'getChildComponents')) {
                    self::trans(
                        items: $item->getChildComponents(),
                        doSnake: $doSnake
                    );
                }

                $item->translate($model, $doSnake);
            })
            ->toArray();
    }

    public static function transFor(string $item, array $replace = []): string
    {
        return __(static::getTranslateModelName() . '.' . $item, $replace);
    }

    public static function transChoiceFor(string $item, int $number, array $replace = []): string
    {
        return trans_choice(static::getTranslateModelName() . '.' . $item, $number, $replace);
    }

    public static function getPluralModelLabel(): string
    {
        return static::getModelLabel();
    }

    public static function getTitleCasePluralModelLabel(): string
    {
        return static::getPluralModelLabel();
    }

    public static function getModelLabel(): string
    {
        return __(static::getTranslateModelName() . '.label.main');
    }

    public static function hasTitleCaseModelLabel(): bool
    {
        return false;
    }
}
