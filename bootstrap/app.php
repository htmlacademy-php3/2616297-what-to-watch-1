<?php

use App\Exceptions\InvalidCredentialsException;
use App\Http\Responses\ErrorResponse;
use App\Http\Responses\ValidationErrorResponse;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (ValidationException $e) {
            return new ValidationErrorResponse(
                $e->getMessage(),
                $e->errors(),
            );
        });

        $exceptions->render(function (InvalidCredentialsException $e) {
            return new ErrorResponse(__('auth.failed'));
        });
    })->create();