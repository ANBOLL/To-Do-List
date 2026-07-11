<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
	->withRouting(
		web: __DIR__ . '/../routes/web.php',
		api: __DIR__ . '/../routes/api.php',
		commands: __DIR__ . '/../routes/console.php',
		health: '/up',
	)
	->withMiddleware(function (Middleware $middleware): void {
		//
	})
	->withExceptions(function (Exceptions $exceptions): void {
		$exceptions->render(function (Throwable $e, Request $request) {
			if (!$request->is('api/*') && !$request->expectsJson()) {
				return;
			}

			$status = 500;
			$message = 'Внутренняя ошибка сервера.';
			$errors = ['server' => ['Произошла непредвиденная ошибка.']];

			if ($e instanceof AuthenticationException) {
				$status = 401;
				$message = 'Не авторизован.';
				$errors = ['auth' => ['Требуется авторизация.']];
			} elseif ($e instanceof AuthorizationException || $e instanceof AccessDeniedHttpException) {
				$status = 403;
				$message = 'Доступ запрещён.';
				$errors = ['auth' => ['У вас нет прав для выполнения этого действия.']];
			} elseif ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
				$status = 404;
				$message = 'Не найдено.';
				$errors = ['resource' => ['Запрошенный ресурс не найден.']];
			} elseif ($e instanceof ValidationException) {
				$status = 422;
				$message = 'Ошибка валидации.';
				$errors = $e->errors();
			}

			return response()->json([
				'message' => $message,
				'errors' => $errors,
				'code' => $status,
			], $status);
		});
	})->create();
