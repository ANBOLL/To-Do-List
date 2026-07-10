<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\Task;
use MoonShine\Laravel\Resources\ModelResource;
use App\MoonShine\Resources\Task\Pages\TaskIndexPage;
use App\MoonShine\Resources\Task\Pages\TaskFormPage;
use MoonShine\MenuManager\Attributes\Group;
use MoonShine\MenuManager\Attributes\Order;
use MoonShine\Support\Attributes\Icon;

#[Icon('clipboard-document-list')]
#[Group('Управление', 'tasks')]
#[Order(2)]
class TaskResource extends ModelResource
{
	protected string $model = Task::class;

	protected string $column = 'title';

	protected int $itemsPerPage = 15;

	protected array $perPageOptions = [15, 25, 50];

	public function getTitle(): string
	{
		return 'Задачи';
	}

	protected function pages(): array
	{
		return [
			TaskIndexPage::class,
			TaskFormPage::class,
		];
	}

	protected function search(): array
	{
		return [
			'title',
			'description',
		];
	}
}
