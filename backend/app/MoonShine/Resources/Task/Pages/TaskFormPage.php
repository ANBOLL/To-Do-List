<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Task\Pages;

use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\Flex;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use MoonShine\UI\Fields\Enum;
use App\MoonShine\Resources\UserResource;

/**
 * @extends FormPage<\App\MoonShine\Resources\TaskResource, \App\Models\Task>
 */
final class TaskFormPage extends FormPage
{
    protected function fields(): iterable
    {
        return [
            Box::make([
                Flex::make([
                    ID::make()->sortable(),
                ]),
                Text::make('Название', 'title')->required(),
                Textarea::make('Описание', 'description'),
                BelongsTo::make('Пользователь', 'user', resource: UserResource::class)
                    ->required()
                    ->searchable(),
                Date::make('Срок', 'due_date'),
                Enum::make('Статус', 'status')
                    ->options([
                        'pending' => 'Ожидает',
                        'in_progress' => 'В работе',
                        'completed' => 'Завершена',
                    ])
                    ->required()
                    ->default('pending'),
            ]),
        ];
    }

    protected function rules(DataWrapperContract $item): array
    {
        return [
            'title' => 'required|string|min:3|max:255',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'due_date' => 'nullable|date',
            'status' => 'required|in:pending,in_progress,completed',
        ];
    }
}
