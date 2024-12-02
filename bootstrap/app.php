<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias(['admin' => \App\Http\Middleware\admin::class]);
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias(['employee' => \App\Http\Middleware\pegawai::class]);
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias(['noback' => \App\Http\Middleware\noBack::class]);
    })->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
    })->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            '/testingAPI123',
        ]);
    })->withMiddleware(function (Middleware $middleware) {
        $middleware->alias(['verifypls' => \App\Http\Middleware\verifypls::class]);
    })->withMiddleware(function (Middleware $middleware) {
        $middleware->alias(['edited' => \App\Http\Middleware\editedLogout::class]);
    })->withMiddleware(function (Middleware $middleware) {
        $middleware->alias(['greateradmin' => \App\Http\Middleware\rootadmin::class]);
    })
    
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
