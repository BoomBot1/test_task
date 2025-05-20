<?php

use App\Http\Middleware\ForceAcceptApplicationJson;
use App\Http\Middleware\HandleEmailAttribute;
use App\Http\Middleware\SanctumGuardMiddleware;
use App\Support\Helpers\Boilerplate;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Sentry\Laravel\Integration;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->appendToGroup('api', [
            HandleEmailAttribute::class,
            ForceAcceptApplicationJson::class,
            SanctumGuardMiddleware::class,
        ]);

        $middleware->redirectGuestsTo('');
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        Integration::handles($exceptions);

        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
            return (new Boilerplate)->requestWantsJson($request);
        });
    })
    ->create();
