<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\User;
use MoonShine\Laravel\Resources\ModelResource;
use App\MoonShine\Resources\User\Pages\UserIndexPage;
use App\MoonShine\Resources\User\Pages\UserFormPage;
use MoonShine\MenuManager\Attributes\Group;
use MoonShine\MenuManager\Attributes\Order;
use MoonShine\Support\Attributes\Icon;

#[Icon('users')]
#[Group('Управление', 'users')]
#[Order(1)]
class UserResource extends ModelResource
{
    protected string $model = User::class;

    protected string $column = 'name';

    protected int $itemsPerPage = 15;

    protected array $perPageOptions = [15, 25, 50];

    public function getTitle(): string
    {
        return 'Пользователи';
    }

    protected function pages(): array
    {
        return [
            UserIndexPage::class,
            UserFormPage::class,
        ];
    }

    protected function search(): array
    {
        return [
            'name',
            'email',
        ];
    }
}
