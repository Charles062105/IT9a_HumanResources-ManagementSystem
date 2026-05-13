<?php

use App\Http\Middleware\CheckIncompleteProfile;
use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsEmployee;
use App\Http\Middleware\SuperAdminOnly;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => IsAdmin::class,
            'employee' => IsEmployee::class,
            'super_admin' => SuperAdminOnly::class,
            'permission' => CheckPermission::class,
        ]);
        $middleware->web(append: CheckIncompleteProfile::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
