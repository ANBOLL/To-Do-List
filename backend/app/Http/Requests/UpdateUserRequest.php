<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
	public function authorize(): bool
	{
		return true;
	}

	public function rules(): array
	{
		return [
			'name' => 'sometimes|string|max:255',
			'email' => 'sometimes|email|max:255|unique:users,email,' . $this->user()->id,
			'password' => 'sometimes|string|min:6',
		];
	}

	public function messages(): array
	{
		return [
			'name.string' => 'Имя должно быть строкой.',
			'name.max' => 'Имя должно быть не более 255 символов.',
			'email.email' => 'Введите корректный email адрес.',
			'email.unique' => 'Этот email уже занят.',
			'email.max' => 'Email должен быть не более 255 символов.',
			'password.min' => 'Пароль должен содержать минимум 6 символов.',
		];
	}
}
