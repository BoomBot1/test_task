<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Brief;
use App\Models\News;
use App\Models\Report;
use App\Models\ReportType;
use App\Models\User;
use App\Services\Integrations\DaData;
use App\Services\Integrations\ReestrApi;
use App\Support\Helpers\Boilerplate;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if (!$this->app->isProduction()) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }

        if (config('integrations.reestr_api.is_fake')) {
            $this->app->bind(ReestrApi\Contracts\ReestrApiService::class, ReestrApi\MockReestrApiService::class);
        } else {
            $this->app->bind(ReestrApi\Contracts\ReestrApiService::class, ReestrApi\ReestrApiService::class);
        }

        if (config('integrations.dadata.is_fake')) {
            $this->app->bind(DaData\Contracts\DaDataService::class, DaData\MockDaDataService::class);
        } else {
            $this->app->bind(DaData\Contracts\DaDataService::class, DaData\DaDataService::class);
        }
    }

    public function boot(): void
    {
        (new Boilerplate)
            ->enforceAppSchema()
            ->configureModels()
            ->configureCommands()
            ->enforceForwardedProto()
            ->logLongDbQuery();

        Relation::enforceMorphMap([
            'admin' => Admin::class,
            'user' => User::class,
            'report-type' => ReportType::class,
            'report' => Report::class,
            'brief' => Brief::class,
            'news' => News::class,
        ]);

        Gate::define('viewLogViewer', static function (?Authenticatable $authenticatable = null) {
            return $authenticatable instanceof Admin;
        });

        Scramble::configure()
            ->withDocumentTransformers(function (OpenApi $openApi) {
                $openApi->secure(
                    SecurityScheme::http('bearer')
                );
            });
    }
}
