<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\User\Pages;

use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\Email;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Enum;

/**
 * @extends IndexPage<\App\MoonShine\Resources\UserResource>
 */
final class UserIndexPage extends IndexPage
{
	protected function fields(): iterable
	{
		return [
			ID::make()->sortable(),
			Text::make('Имя', 'name')->sortable(),
			Email::make('Email', 'email')->sortable(),
			Enum::make('Роль', 'role')
				->options(['user' => 'Пользователь', 'admin' => 'Администратор'])
				->sortable(),
			Date::make('Дата регистрации', 'created_at')->sortable()->format('Y-m-d H:i'),
		];
	}

	protected function search(): array
	{
		return [
			'name',
			'email',
		];
	}

	protected function filters(): iterable
	{
		return [
			Email::make('Email', 'email'),
			Enum::make('Роль', 'role')
				->options(['user' => 'Пользователь', 'admin' => 'Администратор']),
			Date::make('Дата регистрации', 'created_at'),
		];
	}
}
