<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\EditProfile;
use App\Filament\Pages\Auth\Login;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Infolists\Infolist;
use Filament\Navigation\MenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Tables\Table;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\Middleware\ShareErrorsFromSession;

final class AdminPanelProvider extends PanelProvider
{
    public function boot(): void
    {
        Table::$defaultDateTimeDisplayFormat = 'j M. Y H:i';
        Table::$defaultDateDisplayFormat = 'j M. Y';
        Table::$defaultCurrency = 'RUB';
        Table::configureUsing(static function (Table $table) {
            $table->paginated([10, 25, 50, 100]);
        });

        Infolist::$defaultDateTimeDisplayFormat = 'j M. Y H:i';
        Infolist::$defaultDateDisplayFormat = 'j M. Y';
    }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->authGuard('admin-panel')
            ->login(Login::class)
            ->profile(EditProfile::class, false)
            ->colors([
                'primary' => Color::Amber,
            ])
//            ->viteTheme('resources/css/app.css')
            ->discoverResources(
                in: app_path('Filament/Resources'),
                for: 'App\\Filament\\Resources'
            )
            ->discoverPages(
                in: app_path('Filament/Pages'),
                for: 'App\\Filament\\Pages'
            )
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(
                in: app_path('Filament/Widgets'),
                for: 'App\\Filament\\Widgets'
            )
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->userMenuItems(
                array_merge(
                    [],
                    $this->telescopeMenuItem(),
                    $this->horizonMenuItem(),
                    $this->logViewerMenuItem(),
                    $this->scrambleDocsMenuItem(),
                )
            );
    }

    private function telescopeMenuItem(): array
    {
        return config('telescope.enabled')
            ? [
                MenuItem::make()
                    ->label('Telescope')
                    ->url(static fn(): string => route('telescope'))
                    ->visible(static function () {
                        $user = Auth::user();

                        return Gate::allows('viewTelescope', [$user]);
                    })
                    ->icon('heroicon-o-sparkles'),
            ]
            : [];
    }

    private function horizonMenuItem(): array
    {
        return [
            MenuItem::make()
                ->label('Horizon')
                ->url(static fn(): string => route('horizon.index'))
                ->visible(static function () {
                    $user = Auth::user();

                    return Gate::allows('viewHorizon', [$user]);
                })
                ->icon('heroicon-o-rocket-launch'),
        ];
    }

    private function logViewerMenuItem(): array
    {
        return config('log-viewer.enabled')
            ? [
                MenuItem::make()
                    ->label('LogViewer')
                    ->url(static fn(): string => route('log-viewer.index'))
                    ->visible(static function () {
                        $user = Auth::user();

                        return Gate::allows('viewLogViewer', [$user]);
                    })
                    ->icon('heroicon-s-document-text'),
            ]
            : [];
    }

    private function scrambleDocsMenuItem(): array
    {
        return [
            MenuItem::make()
                ->label('API Docs')
                ->url(static fn(): string => route('scramble.docs.ui'))
                ->visible(static function () {
                    $user = Auth::user();

                    return app()->environment('local') || Gate::allows('viewApiDocs', [$user]);
                })
                ->icon('heroicon-o-code-bracket-square'),
        ];
    }
}
