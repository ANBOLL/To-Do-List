<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\User\Pages;

use Illuminate\Validation\Rule;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\Flex;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\Email;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Password;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Enum;

/**
 * @extends FormPage<\App\MoonShine\Resources\UserResource, \App\Models\User>
 */
final class UserFormPage extends FormPage
{
	protected function fields(): iterable
	{
		return [
			Box::make([
				Flex::make([
					ID::make()->sortable(),
				]),
				Flex::make([
					Text::make('Имя', 'name')->required(),
					Email::make('Email', 'email')->required(),
				]),
				Flex::make([
					Password::make('Пароль', 'password')
						->customAttributes(['autocomplete' => 'new-password'])
						->eye(),
				]),
				Flex::make([
					Enum::make('Роль', 'role')
						->options(['user' => 'Пользователь', 'admin' => 'Администратор'])
						->required()
						->default('user'),
				]),
				Date::make('Дата регистрации', 'created_at')
					->format('Y-m-d H:i')
					->default(now()->toDateTimeString()),
			]),
		];
	}

	protected function rules(DataWrapperContract $item): array
	{
		return [
			'name' => 'required|string|max:255',
			'email' => [
				'required',
				'email',
				Rule::unique('users', 'email')->ignoreModel($item->getOriginal()),
			],
			'password' => $item->getKey() !== null ? 'sometimes|nullable|min:8' : 'required|min:8',
			'role' => 'required|in:user,admin',
		];
	}

	protected function afterSave(mixed $item): mixed
	{
		if ($this->getData('password')) {
			$item->update([
				'password' => bcrypt($this->getData('password')),
			]);
		}

		return $item;
	}
}
