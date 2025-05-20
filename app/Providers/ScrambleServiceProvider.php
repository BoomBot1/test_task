<?php

namespace App\Providers;

use App\Models\Admin;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

final class ScrambleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Gate::define('viewApiDocs', static function (?Authenticatable $user = null) {
            return !App::isProduction() || $user instanceof Admin;
        });
    }
}
