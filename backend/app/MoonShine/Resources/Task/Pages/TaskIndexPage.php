<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Task\Pages;

use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Enum;
use App\MoonShine\Resources\UserResource;

/**
 * @extends IndexPage<\App\MoonShine\Resources\TaskResource>
 */
final class TaskIndexPage extends IndexPage
{
    protected function fields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Название', 'title')->sortable(),
            Text::make('Описание', 'description'),
            BelongsTo::make('Пользователь', 'user', resource: UserResource::class),
            Date::make('Срок', 'due_date')->sortable()->format('Y-m-d'),
            Enum::make('Статус', 'status')
                ->options([
                    'pending' => 'Ожидает',
                    'in_progress' => 'В работе',
                    'completed' => 'Завершена',
                ])
                ->sortable(),
            Date::make('Создана', 'created_at')->sortable()->format('Y-m-d H:i'),
        ];
    }

    protected function search(): array
    {
        return [
            'title',
            'description',
        ];
    }

    protected function filters(): iterable
    {
        return [
            Enum::make('Статус', 'status')
                ->options([
                    'pending' => 'Ожидает',
                    'in_progress' => 'В работе',
                    'completed' => 'Завершена',
                ]),
            BelongsTo::make('Пользователь', 'user', resource: UserResource::class),
            Date::make('Срок', 'due_date'),
        ];
    }
}
