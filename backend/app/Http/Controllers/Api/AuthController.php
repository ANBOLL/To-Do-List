<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
	public function login(LoginRequest $request): JsonResponse
	{
		$user = User::where('email', $request->email)->first();

		if (!$user || !Hash::check($request->password, $user->password)) {
			throw ValidationException::withMessages([
				'email' => ['Неверный email или пароль.'],
			]);
		}

		$token = $user->createToken('api-token')->plainTextToken;

		return response()->json([
			'data' => [
				'user' => $user,
				'token' => $token,
			],
		]);
	}

	public function logout(Request $request): JsonResponse
	{
		$token = $request->user()->currentAccessToken();

		if ($token) {
			$token->delete();
		}

		return response()->json([
			'data' => null,
			'message' => 'Logged out successfully.',
		]);
	}

	public function user(Request $request): JsonResponse
	{
		return response()->json(['data' => $request->user()]);
	}

	public function updateUser(UpdateUserRequest $request): JsonResponse
	{
		$user = $request->user();

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
			'data' => $user->fresh(),
			'message' => 'Профиль обновлён.',
		]);
	}
}
