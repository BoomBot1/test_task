<?php

namespace App\Support\Helpers;

use App\Exceptions\BusinessLogicException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Sentry;
use Sentry\Event;
use Sentry\EventHint;
use Throwable;

final class Boilerplate
{
    /**
     * Configure the application's models.
     */
    public function configureModels(): self
    {
        Model::shouldBeStrict();

        return $this;
    }

    /**
     * Configure the application's commands.
     */
    public function configureCommands(): self
    {
        DB::prohibitDestructiveCommands(App::isProduction());

        return $this;
    }

    public function logLongDbQuery(int $ms = 1000): self
    {
        if (App::isProduction()) {
            return $this;
        }

        DB::listen(static function ($query) use ($ms) {
            if ($query->time < $ms) {
                return;
            }

            Log::warning(
                "An individual database query exceeded {$ms} millisecond.",
                ['sql' => $query->sql]
            );
        });

        return $this;
    }

    public function enforceAppSchema(): self
    {
        $schema = str_contains(config('app.url'), 'https://')
            ? 'https'
            : 'http';

        URL::forceScheme($schema);

        return $this;
    }

    /**
     * Configure the images for filament.
     */
    public function enforceForwardedProto(): self
    {
        $appUrl = config('app.url', 'http://localhost');

        // Filament file upload will not work without this
        if (preg_match('/^https:\/\//m', $appUrl)) {
            $param = request()->header('X-Forwarded-Proto', 'https') === 'https'
                ? 'on'
                : 'off';

            request()
                ->server
                ->set('HTTPS', $param);
        }

        return $this;
    }

    public function requestWantsJson(Request $request): bool
    {
        return $request->is('api/*') || $request->expectsJson();
    }

    /**
     * Configure the application's sentry.
     */
    public function callSentry(Throwable $e): void
    {
        if (config('sentry.environment', 'local') !== 'local'
            && app()->bound('sentry')
        ) {
            Sentry\captureException($e);
        }
    }

    public static function sentryBeforeSend(Event $event, ?EventHint $hint): ?Event
    {
        return $hint?->exception instanceof BusinessLogicException && $hint?->exception->getCode() === 422
            ? null
            : $event;
    }
}
