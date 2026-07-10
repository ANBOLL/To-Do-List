<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
	use HasApiTokens, HasFactory, Notifiable;

	protected $fillable = [
		'name',
		'email',
		'password',
		'role',
	];

	protected $hidden = [
		'password',
		'remember_token',
	];

	protected function casts(): array
	{
		return [
			'email_verified_at' => 'datetime',
			'password' => 'hashed',
		];
	}

	protected $appends = ['is_admin'];

	public function getIsAdminAttribute(): bool
	{
		return $this->isAdmin();
	}

	public function isAdmin(): bool
	{
		return $this->role === 'admin';
	}

	protected function serializeDate(\DateTimeInterface $date): string
	{
		return $date->format('Y-m-d H:i:s');
	}

	public function tasks()
	{
		return $this->hasMany(Task::class);
	}
}
