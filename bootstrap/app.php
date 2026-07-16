<?php

use App\Exceptions\CourseNotPublishedException;
use App\Exceptions\NotEnrolledException;
use App\Exceptions\StepNotAccessibleException;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\StaffMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'staff' => StaffMiddleware::class,
        ]);

        $middleware->web(append: [
            SetLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );

        $exceptions->render(function (CourseNotPublishedException $e) {
            abort(404, $e->getMessage());
        });

        $exceptions->render(function (NotEnrolledException $e) {
            abort(403, $e->getMessage());
        });

        $exceptions->render(function (StepNotAccessibleException $e) {
            abort(403, $e->getMessage());
        });
    })->create();
