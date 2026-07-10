# Todo App

Full-stack Todo List приложение с авторизацией, REST API, SPA фронтендом (Nuxt 4) и админ-панелью MoonShine.

## Архитектура

- **Backend:** Laravel 13 + REST API (Sanctum token-based auth)
- **Frontend:** Nuxt 4 SPA (Vue 3 Composition API)
- **Admin Panel:** MoonShine 4
- **Database:** MySQL

## Быстрый старт

```bash
# Backend
cd backend
vcomposer install
cp .env.example .env
# отредактировать .env (БД, URL)
vphp artisan key:generate
vphp artisan migrate --seed
vphp artisan serve

# Frontend (отдельный терминал)
cd frontend
npm install
npm run dev
```

## Установка MoonShine

```bash
cd backend
vcomposer require moonshine/moonshine
vcomposer require moonshine/ru
vphp artisan moonshine:install
vphp artisan moonshine:user  # создать админа для панели
```

## Доступ

| Назначение | URL |
|------------|-----|
| Frontend SPA | http://tr.test.boldyrev.techart.intranet |
| API | http://tr.test.boldyrev.techart.intranet/api |
| Admin Panel | http://tr.test.boldyrev.techart.intranet/admin |

## Тестовые данные

| Роль | Email | Пароль |
|------|-------|--------|
| Администратор (SPA) | admin@example.com | password |
| Пользователь | test@example.com | password |
| Администратор (MoonShine) | создаётся через `artisan moonshine:user` | — |

## API Endpoints

| Метод | Endpoint | Описание |
|-------|----------|----------|
| POST | /api/auth/login | Вход |
| POST | /api/auth/logout | Выход |
| GET | /api/user | Текущий пользователь |
| GET | /api/tasks | Список задач (пагинация, сортировка, фильтр, поиск) |
| POST | /api/tasks | Создать задачу |
| GET | /api/tasks/{id} | Получить задачу |
| PUT | /api/tasks/{id} | Обновить задачу |
| DELETE | /api/tasks/{id} | Удалить задачу |

### Параметры запроса GET /api/tasks

- `page` — номер страницы
- `per_page` — элементов на странице (по умолч. 10, макс. 50)
- `sort_by` — поле сортировки (`due_date`, `status`)
- `filter_by_status` — фильтр по статусу
- `search` — поиск по названию
- `owner_filter` — фильтр владельца для админа (`mine`, `others`)

## Структура проекта

```
├── backend/          # Laravel API + MoonShine
│   ├── app/
│   │   ├── Http/Controllers/Api/   # AuthController, TaskController
│   │   ├── Http/Requests/           # TaskStoreRequest, TaskUpdateRequest
│   │   ├── Models/                  # User, Task
│   │   ├── Policies/                # TaskPolicy
│   │   └── MoonShine/               # Dashboard, Resources (User, Task)
│   ├── database/
│   │   ├── migrations/
│   │   ├── seeders/                 # UserSeeder, TaskSeeder
│   │   └── factories/               # UserFactory, TaskFactory
│   └── routes/
│       ├── api.php
│       └── web.php
├── frontend/         # Nuxt 4 SPA
│   ├── pages/                      # login.vue, dashboard.vue
│   ├── components/                 # TaskList, TaskForm, TaskItem, TaskFilters
│   ├── composables/                # useAuth, useTasks
│   └── middleware/                 # auth.js
└── README.md
```
