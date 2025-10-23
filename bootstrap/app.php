<?php

use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\JsonMiddleware;
use App\Http\Middleware\OptionalAuthenticate;
use App\Http\Middleware\EnsureProfessorIsApproved;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {


        $middleware->appendToGroup('api', [
            JsonMiddleware::class,
            SetLocale::class,
            EnsureFrontendRequestsAreStateful::class,
        ]);
        $middleware->append(\Illuminate\Http\Middleware\HandleCors::class);

        $middleware->alias([
            'optional.auth' => \App\Http\Middleware\OptionalSanctum::class,
            'professor.approved' => EnsureProfessorIsApproved::class,
            'wants_json' => JsonMiddleware::class,
            'api.auth' => \App\Http\Middleware\ApiAuthenticate::class,

        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();



