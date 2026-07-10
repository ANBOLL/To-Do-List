<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
	public function login(Request $request): JsonResponse
	{
		$request->validate([
			'email' => 'required|email',
			'password' => 'required',
		], [
			'email.required' => 'Поле Email обязательно для заполнения.',
			'email.email' => 'Введите корректный email адрес.',
			'password.required' => 'Поле Пароль обязательно для заполнения.',
		]);

		$user = User::where('email', $request->email)->first();

		if (!$user || !Hash::check($request->password, $user->password)) {
			throw ValidationException::withMessages([
				'email' => ['Неверный email или пароль.'],
			]);
		}

		$token = $user->createToken('api-token')->plainTextToken;

		return response()->json([
			'user' => $user,
			'token' => $token,
		]);
	}

	public function logout(Request $request): JsonResponse
	{
		$request->user()->currentAccessToken()->delete();

		return response()->json(['message' => 'Logged out successfully.']);
	}

	public function user(Request $request): JsonResponse
	{
		return response()->json(['user' => $request->user()]);
	}

	public function updateUser(Request $request): JsonResponse
	{
		$user = $request->user();

		$request->validate([
			'name' => 'sometimes|string|max:255',
			'email' => 'sometimes|email|max:255|unique:users,email,' . $user->id,
			'password' => 'sometimes|string|min:6',
		], [
			'name.string' => 'Имя должно быть строкой.',
			'name.max' => 'Имя должно быть не более 255 символов.',
			'email.email' => 'Введите корректный email адрес.',
			'email.unique' => 'Этот email уже занят.',
			'email.max' => 'Email должен быть не более 255 символов.',
			'password.min' => 'Пароль должен содержать минимум 6 символов.',
		]);

		if ($request->has('name')) {
			$user->name = $request->name;
		}

		if ($request->has('email')) {
			$user->email = $request->email;
		}

		if ($request->has('password')) {
			$user->password = Hash::make($request->password);
		}

		$user->save();

		return response()->json([
			'user' => $user->fresh(),
			'message' => 'Профиль обновлён.',
		]);
	}
}
