<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
	public function index(Request $request): JsonResponse
	{
		$userFields = $request->user()->isAdmin() ? 'user:id,email,name' : 'user:id,email';
		$query = Task::with($userFields);

		if (!$request->user()->isAdmin()) {
			$query->where('user_id', $request->user()->id);
		}

		if ($request->filled('owner_filter') && $request->user()->isAdmin()) {
			if ($request->owner_filter === 'mine') {
				$query->where('user_id', $request->user()->id);
			} elseif ($request->owner_filter === 'others') {
				$query->where('user_id', '!=', $request->user()->id);
			}
		}

		if ($request->filled('search')) {
			$query->where('title', 'like', '%' . $request->search . '%');
		}

		if ($request->filled('filter_by_status')) {
			$query->where('status', $request->filter_by_status);
		}

		$sortBy = $request->input('sort_by');
		$sortDir = $request->input('sort_direction', 'asc');
		$sortDir = in_array($sortDir, ['asc', 'desc']) ? $sortDir : 'asc';

		if ($sortBy && in_array($sortBy, ['created_at', 'due_date', 'status'])) {
			if ($sortBy === 'due_date') {
				$query->orderByRaw('CASE WHEN due_date IS NULL THEN 1 ELSE 0 END')
					->orderBy('due_date', $sortDir);
			} elseif ($sortBy === 'status') {
				$dir = $sortDir === 'asc' ? 'ASC' : 'DESC';
				$query->orderByRaw("CASE status WHEN 'pending' THEN 1 WHEN 'in_progress' THEN 2 WHEN 'completed' THEN 3 END $dir");
			} else {
				$query->orderBy($sortBy, $sortDir);
			}
		} else {
			$query->latest();
		}

		$perPage = min((int) $request->input('per_page', 10), 50);
		$tasks = $query->paginate($perPage);

		return response()->json([
			'data' => $tasks->items(),
			'meta' => [
				'total' => $tasks->total(),
				'per_page' => $tasks->perPage(),
				'current_page' => $tasks->currentPage(),
				'last_page' => $tasks->lastPage(),
			],
		]);
	}

	public function store(TaskStoreRequest $request): JsonResponse
	{
		$task = $request->user()->tasks()->create($request->validated());

		return response()->json([
			'data' => $task->load('user:id,email'),
		], 201);
	}

	public function show(Request $request, Task $task): JsonResponse
	{
		$this->authorize('view', $task);

		return response()->json([
			'data' => $task->load('user:id,email'),
		]);
	}

	public function update(TaskUpdateRequest $request, Task $task): JsonResponse
	{
		$this->authorize('update', $task);

		$task->update($request->validated());

		return response()->json([
			'data' => $task->fresh()->load('user:id,email'),
		]);
	}

	public function destroy(Request $request, Task $task): JsonResponse
	{
		$this->authorize('delete', $task);

		$task->delete();

		return response()->json([
			'data' => null,
			'message' => 'Задача удалена.',
		]);
	}
}
