<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Middleware de alias — acessível como 'admin' e 'check.ativo' nas rotas
        $middleware->alias([
            'admin'       => \App\Http\Middleware\AdminMiddleware::class,
            'check.ativo' => \App\Http\Middleware\CheckUserAtivo::class,
        ]);

        // Aplica a verificação de usuário ativo em todas as requisições web autenticadas
        $middleware->appendToGroup('web', \App\Http\Middleware\CheckUserAtivo::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
