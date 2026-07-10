<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskStoreRequest extends FormRequest
{
	public function authorize(): bool
	{
		return true;
	}

	public function rules(): array
	{
		return [
			'title' => 'required|string|min:3|max:255',
			'description' => 'nullable|string',
			'due_date' => 'nullable|date',
			'status' => 'required|in:pending,in_progress,completed',
		];
	}

	public function messages(): array
	{
		return [
			'title.required' => 'Название обязательно для заполнения.',
			'title.min' => 'Название должно содержать минимум 3 символа.',
			'title.max' => 'Название должно быть не более 255 символов.',
			'due_date.date' => 'Введите корректную дату.',
			'status.required' => 'Статус обязателен для заполнения.',
			'status.in' => 'Выбран недопустимый статус.',
		];
	}
}
