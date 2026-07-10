<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
{
	use RefreshDatabase;

	protected User $user;
	protected User $admin;
	protected string $token;

	protected function setUp(): void
	{
		parent::setUp();

		$this->user = User::factory()->create(['role' => 'user']);
		$this->admin = User::factory()->admin()->create();

		$this->token = $this->user->createToken('test')->plainTextToken;
	}

	public function test_login_returns_token(): void
	{
		$user = User::factory()->create(['password' => bcrypt('password')]);

		$response = $this->postJson('/api/auth/login', [
			'email' => $user->email,
			'password' => 'password',
		]);

		$response->assertOk()->assertJsonStructure(['token', 'user']);
	}

	public function test_login_fails_with_invalid_credentials(): void
	{
		$response = $this->postJson('/api/auth/login', [
			'email' => 'wrong@test.com',
			'password' => 'wrong',
		]);

		$response->assertStatus(422);
	}

	public function test_unauthenticated_request_returns_401(): void
	{
		$response = $this->getJson('/api/tasks');

		$response->assertStatus(401);
	}

	public function test_can_list_tasks(): void
	{
		Task::factory(3)->create(['user_id' => $this->user->id]);

		$response = $this->getJson('/api/tasks', [
			'Authorization' => "Bearer $this->token",
		]);

		$response->assertOk()->assertJsonStructure(['data', 'meta']);
		$this->assertCount(3, $response->json('data'));
	}

	public function test_can_create_task(): void
	{
		$response = $this->postJson('/api/tasks', [
			'title' => 'Test Task',
			'status' => 'pending',
		], [
			'Authorization' => "Bearer $this->token",
		]);

		$response->assertCreated()->assertJsonPath('data.title', 'Test Task');
	}

	public function test_create_task_validates_title(): void
	{
		$response = $this->postJson('/api/tasks', [
			'title' => 'ab',
			'status' => 'pending',
		], [
			'Authorization' => "Bearer $this->token",
		]);

		$response->assertStatus(422);
	}

	public function test_can_view_own_task(): void
	{
		$task = Task::factory()->create(['user_id' => $this->user->id]);

		$response = $this->getJson("/api/tasks/$task->id", [
			'Authorization' => "Bearer $this->token",
		]);

		$response->assertOk()->assertJsonPath('data.id', $task->id);
	}

	public function test_cannot_view_others_task(): void
	{
		$other = User::factory()->create();
		$task = Task::factory()->create(['user_id' => $other->id]);

		$response = $this->getJson("/api/tasks/$task->id", [
			'Authorization' => "Bearer $this->token",
		]);

		$response->assertStatus(403);
	}

	public function test_can_update_own_task(): void
	{
		$task = Task::factory()->create(['user_id' => $this->user->id]);

		$response = $this->putJson("/api/tasks/$task->id", [
			'title' => 'Updated Title',
		], [
			'Authorization' => "Bearer $this->token",
		]);

		$response->assertOk()->assertJsonPath('data.title', 'Updated Title');
	}

	public function test_cannot_update_others_task(): void
	{
		$other = User::factory()->create();
		$task = Task::factory()->create(['user_id' => $other->id]);

		$response = $this->putJson("/api/tasks/$task->id", [
			'title' => 'Hacked',
		], [
			'Authorization' => "Bearer $this->token",
		]);

		$response->assertStatus(403);
	}

	public function test_can_delete_own_task(): void
	{
		$task = Task::factory()->create(['user_id' => $this->user->id]);

		$response = $this->deleteJson("/api/tasks/$task->id", [], [
			'Authorization' => "Bearer $this->token",
		]);

		$response->assertOk();
		$this->assertModelMissing($task);
	}

	public function test_cannot_delete_others_task(): void
	{
		$other = User::factory()->create();
		$task = Task::factory()->create(['user_id' => $other->id]);

		$response = $this->deleteJson("/api/tasks/$task->id", [], [
			'Authorization' => "Bearer $this->token",
		]);

		$response->assertStatus(403);
	}

	public function test_admin_can_view_any_task(): void
	{
		$other = User::factory()->create();
		$task = Task::factory()->create(['user_id' => $other->id]);
		$adminToken = $this->admin->createToken('test')->plainTextToken;

		$response = $this->getJson("/api/tasks/$task->id", [
			'Authorization' => "Bearer $adminToken",
		]);

		$response->assertOk();
	}

	public function test_admin_can_update_any_task(): void
	{
		$other = User::factory()->create();
		$task = Task::factory()->create(['user_id' => $other->id]);
		$adminToken = $this->admin->createToken('test')->plainTextToken;

		$response = $this->putJson("/api/tasks/$task->id", [
			'title' => 'Admin Update',
		], [
			'Authorization' => "Bearer $adminToken",
		]);

		$response->assertOk();
	}

	public function test_admin_sees_all_tasks(): void
	{
		Task::factory(2)->create(['user_id' => $this->user->id]);
		Task::factory(2)->create(['user_id' => $this->admin->id]);
		$adminToken = $this->admin->createToken('test')->plainTextToken;

		$response = $this->getJson('/api/tasks', [
			'Authorization' => "Bearer $adminToken",
		]);

		$this->assertCount(4, $response->json('data'));
	}
}
