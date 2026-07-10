<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
	public function run(): void
	{
		User::firstOrCreate(
			['email' => 'admin@example.com'],
			[
				'name' => 'Admin User',
				'password' => bcrypt('password'),
				'role' => 'admin',
			]
		);

		User::firstOrCreate(
			['email' => 'test@example.com'],
			[
				'name' => 'Test User',
				'password' => bcrypt('password'),
				'role' => 'user',
			]
		);

		if (User::count() < 10) {
			User::factory(8)->create();
		}
	}
}
