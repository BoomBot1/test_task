<?php

namespace App\Filament\Support\Forms;

use Carbon\CarbonInterface;
use Closure;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Database\Eloquent\Model;

trait HasPreConfiguredForm
{
    public static function makePreConfiguredFormWithGroup(
        Form $form,
        array|Closure $mainSchema,
        array|Closure $sideSchema,
    ): Form {
        return $form
            ->columns(3)
            ->schema([
                Group::make()
                    ->schema($mainSchema)
                    ->columnSpan(self::columnSpanConfig()),

                self::makePreConfiguredSideCard($sideSchema),
            ]);
    }

    public static function makePreConfiguredFormWithTabs(
        Form $form,
        Tabs $mainTabs,
        array|Closure $sideSchema,
    ): Form {
        return $form
            ->columns(3)
            ->schema([
                $mainTabs
                    ->columnSpan(self::columnSpanConfig()),

                self::makePreConfiguredSideCard($sideSchema),
            ]);
    }

    public static function makePreConfiguredForm(
        Form $form,
        array|Closure $mainSchema,
        array|Closure $sideSchema,
    ): Form {
        return $form
            ->columns(3)
            ->schema([
                Section::make()
                    ->schema($mainSchema)
                    ->columnSpan(self::columnSpanConfig()),

                self::makePreConfiguredSideCard($sideSchema),
            ]);
    }

    public static function makePreConfiguredSideCard(array|Closure $schema): Section
    {
        return Section::make()
            ->schema($schema)
            ->hidden(static fn(?Model $record) => $record === null)
            ->columnSpan(['lg' => 1]);
    }

    public static function makePreConfiguredPlaceholder(string $attribute): Placeholder
    {
        return match ($attribute) {
            'id' => Placeholder::make($attribute)
                ->content(static function (?Model $record) use ($attribute) {
                    return $record->{$attribute};
                }),
            'state' => self::makeConfiguredPlaceholderAsLabel($attribute),

            'created_at',
            'published_at',
            'updated_at',
            'deleted_at' => self::makeConfiguredPlaceholderDateTime($attribute),
        };
    }

    public static function makeConfiguredPlaceholderAsLabel(string $attribute): Placeholder
    {
        return Placeholder::make($attribute)
            ->content(static function (?Model $record) use ($attribute) {
                if (blank($record?->{$attribute})) {
                    return '-';
                }

                $value = $record->{$attribute};

                if (!($value instanceof HasLabel)) {
                    throw new \InvalidArgumentException("{$attribute} must implement " . HasLabel::class);
                }

                return $value->getLabel();
            });
    }

    public static function makeConfiguredPlaceholderDateTime(string $attribute): Placeholder
    {
        return Placeholder::make($attribute)
            ->content(static function (?Model $record) use ($attribute) {
                if (blank($record?->{$attribute})) {
                    return '-';
                }

                $value = $record->{$attribute};

                if (!($value instanceof CarbonInterface)) {
                    throw new \InvalidArgumentException("{$attribute} must implement " . CarbonInterface::class);
                }

                return $value->diffForHumans();
            });
    }

    private static function columnSpanConfig(): array
    {
        return [
            'lg' => static function (?Model $record) {
                return $record === null ? 3 : 2;
            },
        ];
    }
}
