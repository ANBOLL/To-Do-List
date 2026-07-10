<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|min:3|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date|after:today',
            'status' => 'sometimes|required|in:pending,in_progress,completed',
        ];
    }
}
