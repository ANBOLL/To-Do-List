<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    private static array $russianTitles = [
        'Написать отчёт по проекту',
        'Обновить документацию API',
        'Исправить ошибку авторизации',
        'Добавить тёмную тему',
        'Оптимизировать запросы к БД',
        'Настроить CI/CD pipeline',
        'Провести код-ревью',
        'Обновить зависимости',
        'Добавить валидацию форм',
        'Реализовать экспорт в PDF',
        'Написать unit-тесты',
        'Настроить мониторинг',
        'Обновить дизайн главной',
        'Добавить поиск по сайту',
        'Реализовать загрузку файлов',
        'Исправить баг с пагинацией',
        'Добавить уведомления',
        'Обновить политику безопасности',
        'Настроить кэширование',
        'Реализовать REST API',
        'Добавить поддержку Telegram',
        'Оптимизировать загрузку',
        'Исправить вёрстку',
        'Добавить мультиязычность',
        'Написать миграции',
    ];

    private static array $russianDescriptions = [
        'Необходимо выполнить в соответствии с ТЗ.',
        'Срок выполнения — до конца недели.',
        'После завершения уведомить команду.',
        'Требуется согласование с архитектором.',
        'Есть пример реализации в соседнем проекте.',
        'Баг воспроизводится в окружении production.',
    ];

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->randomElement(self::$russianTitles),
            'description' => fake()->boolean(50) ? fake()->randomElement(self::$russianDescriptions) : null,
            'due_date' => fake()->boolean(70) ? fake()->dateTimeBetween('+1 day', '+1 month')->format('Y-m-d') : null,
            'status' => fake()->randomElement(['pending', 'in_progress', 'completed']),
        ];
    }
}
