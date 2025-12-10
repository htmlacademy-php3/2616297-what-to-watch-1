<?php

declare(strict_types=1);

use App\Exceptions\InvalidCredentialsException;
use App\Http\Responses\ErrorResponse;
use App\Http\Responses\NotFoundErrorResponse;
use App\Http\Responses\ValidationErrorResponse;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (ValidationException $e) {
            return new ValidationErrorResponse(
                $e->getMessage(),
                $e->errors(),
            );
        });

        $exceptions->render(function (AccessDeniedHttpException $e) {
            return new ErrorResponse(
                __('http-statuses.403'),
                Response::HTTP_FORBIDDEN,
            );
        });

        $exceptions->render(function (NotFoundHttpException $e) {
            return new NotFoundErrorResponse(
                __('http-statuses.404')
            );
        });

        $exceptions->render(function (UnauthorizedException $e) {
            return new ErrorResponse(
                __('http-statuses.403'),
                Response::HTTP_FORBIDDEN,
            );
        });

        $exceptions->render(function (InvalidCredentialsException $e) {
            return new ErrorResponse(__('auth.failed'));
        });
    })->create();