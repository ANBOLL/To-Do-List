<?php

declare(strict_types=1);

namespace App\MoonShine\Layouts;

use MoonShine\Laravel\Layouts\AppLayout;
use MoonShine\ColorManager\Palettes\PurplePalette;
use MoonShine\ColorManager\ColorManager;
use MoonShine\Contracts\ColorManager\ColorManagerContract;
use MoonShine\Contracts\ColorManager\PaletteContract;
use MoonShine\MenuManager\MenuGroup;
use MoonShine\MenuManager\MenuItem;
use App\MoonShine\Resources\UserResource;
use App\MoonShine\Resources\TaskResource;
use App\MoonShine\Resources\MoonShineUser\MoonShineUserResource;
use App\MoonShine\Resources\MoonShineUserRole\MoonShineUserRoleResource;

final class MoonShineLayout extends AppLayout
{
	/**
	 * @var null|class-string<PaletteContract>
	 */
	protected ?string $palette = PurplePalette::class;

	protected function assets(): array
	{
		return [
			...parent::assets(),
		];
	}

	protected function menu(): array
	{
		return [
			MenuGroup::make('Управление', [
				MenuItem::make(UserResource::class, 'Пользователи'),
				MenuItem::make(TaskResource::class, 'Задачи'),
			]),

			MenuGroup::make('Система', [
				MenuItem::make(MoonShineUserResource::class),
				MenuItem::make(MoonShineUserRoleResource::class),
			]),
		];
	}

	/**
	 * @param ColorManager $colorManager
	 */
	protected function colors(ColorManagerContract $colorManager): void
	{
		parent::colors($colorManager);
	}
}
