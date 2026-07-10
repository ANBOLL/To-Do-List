<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\Task;
use App\Models\User;
use MoonShine\Laravel\Pages\Page;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\UI\Components\Layout\Divider;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Components\Metrics\Wrapped\ValueMetric;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\Enum;

#[\MoonShine\MenuManager\Attributes\SkipMenu]
class Dashboard extends Page
{
	public function getBreadcrumbs(): array
	{
		return [
			'#' => $this->getTitle()
		];
	}

	public function getTitle(): string
	{
		return $this->title ?: 'Панель управления';
	}

	protected function components(): iterable
	{
		$totalUsers = User::count();
		$totalTasks = Task::count();
		$pendingTasks = Task::where('status', 'pending')->count();
		$inProgressTasks = Task::where('status', 'in_progress')->count();
		$completedTasks = Task::where('status', 'completed')->count();

		$recentTasks = Task::with('user:id,email')->latest()->take(5)->get();
		$recentUsers = User::latest()->take(5)->get();

		$tasksByDay = Task::selectRaw('DATE(created_at) as date, COUNT(*) as count')
			->where('created_at', '>=', now()->subDays(7))
			->groupBy('date')
			->orderBy('date')
			->get();

		return [
			Grid::make([
				Column::make([
					ValueMetric::make('Всего пользователей')
						->value($totalUsers)
						->icon('users'),
				])->columnSpan(3),

				Column::make([
					ValueMetric::make('Всего задач')
						->value($totalTasks)
						->icon('clipboard-document-list'),
				])->columnSpan(3),

				Column::make([
					ValueMetric::make('Ожидают')
						->value($pendingTasks)
						->icon('clock'),
				])->columnSpan(2),

				Column::make([
					ValueMetric::make('В работе')
						->value($inProgressTasks)
						->icon('play-circle'),
				])->columnSpan(2),

				Column::make([
					ValueMetric::make('Завершено')
						->value($completedTasks)
						->icon('check-circle'),
				])->columnSpan(2),

				Column::make([
					Divider::make('Задачи по дням (последние 7 дней)'),
					TableBuilder::make()
						->fields([
							Date::make('Дата', 'date'),
							Text::make('Количество', 'count'),
						])
						->items($tasksByDay)
						->simple()
						->vertical()
						->preview(),

					Divider::make('Последние задачи'),
					TableBuilder::make()
						->fields([
							Text::make('Название', 'title'),
							Text::make('Пользователь', 'user.email'),
							Enum::make('Статус', 'status')
								->options([
									'pending' => 'Ожидает',
									'in_progress' => 'В работе',
									'completed' => 'Завершена',
								]),
							Date::make('Создана', 'created_at')->format('Y-m-d'),
						])
						->items($recentTasks)
						->simple()
						->vertical()
						->preview(),

					Divider::make('Новые пользователи'),
					TableBuilder::make()
						->fields([
							Text::make('Имя', 'name'),
							Text::make('Email', 'email'),
							Date::make('Зарегистрирован', 'created_at')->format('Y-m-d'),
						])
						->items($recentUsers)
						->simple()
						->vertical()
						->preview(),

					Divider::make('Быстрые действия'),
					ActionButton::make('Создать задачу', '/admin/resource/task-resource/task-form-page')
						->primary()
						->icon('plus'),
					ActionButton::make('Создать пользователя', '/admin/resource/user-resource/user-form-page')
						->secondary()
						->icon('plus'),
				])->columnSpan(12),
			]),
		];
	}
}
