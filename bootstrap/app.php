<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->appendToGroup('api', \App\Http\Middleware\ValidateHeader::class,\App\Http\Middleware\ValidateDocumentMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (ValidationException $exception) {
            $title = $exception->getMessage();

            return response()->json([
                'errors' => collect($exception->errors())
                    ->map(function($messages, $field) use ($title) {
                        return [
                            'title' => $title,
                            'detail' => $messages[0],
                            'source' => [
                                'pointer' => "/".str_replace('.', '/', $field)
                            ]
                        ];
                    })->values()
            ], 422,[
                'content-type' => 'application/vnd.api+json'
            ]);
        });
    })->create();