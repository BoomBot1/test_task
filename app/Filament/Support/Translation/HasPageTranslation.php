<?php

namespace App\Filament\Support\Translation;

use Filament\Pages\Page;

/**
 * @mixin Page
 *
 * @noinspection PhpUnused
 */
trait HasPageTranslation
{
    use HasLang;

    abstract public static function getTranslateModelName(): string;

    public static function getModelLabel(): string
    {
        return static::transFor('label.main');
    }

    public function getTitle(): string
    {
        return self::transFor('label.main');
    }

    public static function getClusterBreadcrumb(): ?string
    {
        return static::getModelLabel();
    }

    public static function getNavigationLabel(): string
    {
        return static::getModelLabel();
    }

    public static function transFor(string $item, array $replace = []): string
    {
        return __(static::getTranslateModelName() . '.' . $item, $replace);
    }

    public static function trans(
        array $items,
        string $additional = '',
        bool $doSnake = false,
    ): array {
        $key = static::getTranslateModelName() . $additional;

        return collect($items)
            ->each(static function ($item) use ($key, $doSnake) {
                if (method_exists($item, 'getChildComponents')) {
                    self::trans(
                        items: $item->getChildComponents(),
                        doSnake: $doSnake
                    );
                }

                $item->translate($key, $doSnake);
            })
            ->toArray();
    }
}
