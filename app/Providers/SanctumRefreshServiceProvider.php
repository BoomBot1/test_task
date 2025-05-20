<?php

namespace App\Providers;

use App\Console\Commands\PruneRefreshExpired;
use App\Models\PersonalRefreshToken;
use App\Observers\RefreshTokenObserver;
use Illuminate\Support\ServiceProvider;

final class SanctumRefreshServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        PersonalRefreshToken::observe([RefreshTokenObserver::class]);

        if ($this->app->runningInConsole()) {
            $this->publishesMigrations([
                __DIR__ . '/../../database/migrations' => database_path('migrations'),
            ], 'sanctum-refresh-migrations');

            $this->commands([
                PruneRefreshExpired::class,
            ]);
        }
    }
}
