<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
	protected $dontFlash = [
		'current_password',
		'password',
		'password_confirmation',
	];

	public function register(): void
	{
		$this->reportable(function (Throwable $e) {});
	}

	public function render($request, Throwable $e)
	{
		if ($request->expectsJson() || $request->is('api/*')) {
			return $this->handleApiException($e);
		}

		return parent::render($request, $e);
	}

	private function handleApiException(Throwable $e)
	{
		if ($e instanceof AuthenticationException) {
			return response()->json([
				'message' => 'Не авторизован.',
				'errors' => ['auth' => ['Требуется авторизация.']]
			], 401);
		}

		if ($e instanceof AuthorizationException) {
			return response()->json([
				'message' => 'Доступ запрещён.',
				'errors' => ['auth' => ['У вас нет прав для выполнения этого действия.']]
			], 403);
		}

		if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
			return response()->json([
				'message' => 'Не найдено.',
				'errors' => ['resource' => ['Запрошенный ресурс не найден.']]
			], 404);
		}

		if ($e instanceof ValidationException) {
			return response()->json([
				'message' => 'Ошибка валидации.',
				'errors' => $e->errors(),
			], 422);
		}

		if (config('app.debug')) {
			return parent::render(request(), $e);
		}

		return response()->json([
			'message' => 'Внутренняя ошибка сервера.',
			'errors' => ['server' => ['Произошла непредвиденная ошибка.']]
		], 500);
	}
}
