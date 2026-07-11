<div align="center">
  <br>
  <img src="frontend/public/icons/icon.svg" width="80" height="80" alt="To-Do List">
  <h1 align="center">To-Do List</h1>
  <p align="center">Fullstack-приложение для управления задачами</p>
  <p align="center">
    Laravel + Nuxt 4 + Sanctum + SQLite
  </p>
  <br>
</div>

## Содержание

- [О проекте](#о-проекте)
- [Стек технологий](#стек-технологий)
- [Быстрый старт](#быстрый-старт)
- [Тестовые пользователи](#тестовые-пользователи)
- [Docker](#docker)
- [API Endpoints](#api-endpoints)
- [Swagger](#swagger)
- [Auth-подход](#auth-подход)
- [Структура проекта](#структура-проекта)
- [Тестирование](#тестирование)
- [Админ-панель (MoonShine)](#админ-панель-moonshine)
- [Дополнительные возможности](#дополнительные-возможности)

---

## О проекте

Мини-приложение «Список задач» с авторизацией, CRUD-операциями и клиентской частью на Nuxt 4.

- **Laravel** — данные, авторизация, бизнес-правила, REST API
- **Nuxt 4** — интерфейс, взаимодействие с API, роутинг

---

## Стек технологий

| Компонент | Технология |
|---|---|
| **Backend** | Laravel 13, PHP 8.4+, Eloquent ORM |
| **Frontend** | Nuxt 4, Vue 3 Composition API, Vite |
| **База данных** | SQLite (по умолчанию) / MySQL |
| **Авторизация** | Laravel Sanctum (Bearer Token) |
| **Валидация** | Form Request |
| **Стили** | BEM-методология, CSS-переменные, responsive (4 брейкпоинта) |

---

## Быстрый старт

### 1. Backend

```bash
cd backend

# Установка зависимостей
composer install

# Настройка окружения
cp .env.example .env
php artisan key:generate

# База данных (SQLite — по умолчанию)
# Файл БД создаётся автоматически при миграции

# Миграции + сиды (тестовые пользователи + задачи)
php artisan migrate --seed

# Запуск сервера
php artisan serve
```

Backend будет доступен на **http://localhost:8000**.

### 2. Frontend

```bash
cd frontend

# Установка зависимостей
npm install

# Запуск dev-сервера (прокси на backend настроен в nuxt.config.ts)
npm run dev
```

Frontend будет доступен на **http://localhost:5173**.  
В dev-режиме `.env` не требуется — запросы к API проксируются через Vite (`vite.server.proxy` в `nuxt.config.ts`).

---

## Тестовые пользователи

| Роль | Email | Пароль |
|---|---|---|
| **Администратор** | `admin@example.com` | `password` |
| **Пользователь** | `test@example.com` | `password` |

- **Admin** видит все задачи пользователей, может редактировать/удалять любые
- **User** видит только свои задачи

---

## Docker

- Backend: **http://localhost:8000**
- Frontend: **http://localhost:5173**

### Docker + SQLite (по умолчанию)

```bash
docker compose up -d
docker compose exec app php artisan migrate --seed
```

### Docker + MySQL

Раскомментировать MySQL-блок в `docker-compose.yml` и добавить сервис MySQL:

```yaml
services:
  mysql:
    image: mysql:8.0
    container_name: todo-mysql
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: todo_app_test
    ports:
      - "3307:3306"
    networks:
      - todo-network
```

После чего в блоке `app` → `environment` раскомментировать MySQL:

```yaml
- DB_CONNECTION=mysql
- DB_HOST=mysql
- DB_PORT=3306
- DB_DATABASE=todo_app_test
- DB_USERNAME=root
- DB_PASSWORD=root
```

Запуск:

```bash
docker compose up -d
docker compose exec app php artisan migrate --seed
```

### Local (без Docker)

Настройки БД в файле `backend/.env`. Docker не влияет.

**SQLite:**
```bash
cd backend
cp .env.example .env
# в .env раскомментировать SQLite-блок, закомментировать MySQL
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

**MySQL:**
```bash
cd backend
cp .env.example .env
# в .env раскомментировать MySQL-блок, закомментировать SQLite
# предварительно создать БД todo_app_test
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

### Особенности Docker-окружения

- **Прокси**: `/api/*` запросы проксируются через `vite.server.proxy` (не `nitro.devProxy` — удалён в Nuxt 4)
- **OPcache**: сконфигурирован с `revalidate_freq=30` для ускорения на Windows bind mount (`backend/docker-php-ext-opcache.ini`)
- **Скорость**: `SESSION_DRIVER=file`, `CACHE_STORE=file` — чтобы не дёргать SQLite на каждый запрос

---

## API Endpoints

Все endpoints возвращают JSON. Авторизованные запросы требуют заголовок:

```
Authorization: Bearer <token>
```

### Auth

| Method | Path | Доступ | Описание |
|---|---|---|---|
| `POST` | `/api/auth/login` | public | Вход, получение Bearer-токена |
| `POST` | `/api/auth/logout` | auth | Завершение сессии |
| `GET` | `/api/user` | auth | Текущий пользователь |
| `PUT` | `/api/user` | auth | Обновление профиля (имя, email, пароль) |

### Tasks

| Method | Path | Доступ | Описание |
|---|---|---|---|
| `GET` | `/api/tasks` | auth | Список задач (с пагинацией, фильтрацией, поиском) |
| `POST` | `/api/tasks` | auth | Создание задачи |
| `GET` | `/api/tasks/{id}` | auth | Получение задачи |
| `PUT/PATCH` | `/api/tasks/{id}` | owner/admin | Редактирование задачи |
| `DELETE` | `/api/tasks/{id}` | owner/admin | Удаление задачи |

### Параметры GET /api/tasks

| Параметр | Тип | Описание |
|---|---|---|
| `page` | integer | Номер страницы |
| `per_page` | integer (max 50) | Задач на странице |
| `sort_by` | `created_at` / `due_date` / `status` | Поле сортировки |
| `sort_direction` | `asc` / `desc` | Направление сортировки (по умолчанию `asc`) |
| `filter_by_status` | `pending` / `in_progress` / `completed` | Фильтр по статусу |
| `search` | string | Поиск по названию |
| `owner_filter` | `mine` / `others` | Фильтр по владельцу (только admin) |

### Формат ответов

**Успех (200/201):**
```json
{
  "data": { ... },
  "meta": { "total": 12, "per_page": 10, "current_page": 1, "last_page": 2 }
}
```

**Ошибки:**

| Код | Описание |
|---|---|
| `401` | `{"message": "Не авторизован.", "errors": {...}, "code": 401}` |
| `403` | `{"message": "Доступ запрещён.", "errors": {...}, "code": 403}` |
| `404` | `{"message": "Не найдено.", "errors": {...}, "code": 404}` |
| `422` | `{"message": "Ошибка валидации.", "errors": {...}, "code": 422}` |
| `500` | `{"message": "Внутренняя ошибка сервера.", "errors": {...}, "code": 500}` |

---

## Swagger

Документация API в формате OpenAPI 3.0:

- **Spec**: `/swagger.json`
- **UI**: http://localhost:8000/api/documentation

---

## Auth-подход

Используется **Laravel Sanctum** в режиме Bearer Token:

1. При логине (`POST /api/auth/login`) сервер возвращает `plainTextToken` и данные пользователя
2. Токен сохраняется в **localStorage** браузера
3. Каждый запрос к API отправляется с заголовком `Authorization: Bearer <token>`
4. При ответе 401 — токен удаляется, пользователь перенаправляется на страницу входа
5. Sanctum не использует куки и CSRF — подходит для SPA на отдельном домене/порте

---

## Структура проекта

```
todo-list/
├── backend/                        # Laravel API
│   ├── app/
│   │   ├── Exceptions/             # Обработка ошибок (401, 403, 404, 422, 500)
│   │   ├── Http/
│   │   │   ├── Controllers/Api/    # AuthController, TaskController
│   │   │   └── Requests/           # LoginRequest, TaskStoreRequest, TaskUpdateRequest, UpdateUserRequest
│   │   ├── Models/                 # User, Task
│   │   ├── Policies/               # TaskPolicy (owner/admin)
│   │   ├── Providers/              # AppServiceProvider, MoonShineServiceProvider
│   │   ├── Enums/                  # TaskStatus, UserRole
│   │   └── MoonShine/              # Dashboard, Layouts, Resources
│   ├── config/                     # moonshine.php, auth.php, cors.php
│   ├── database/
│   │   ├── factories/              # UserFactory, TaskFactory
│   │   ├── migrations/             # users, tasks, personal_access_tokens
│   │   └── seeders/                # UserSeeder, TaskSeeder
│   ├── lang/ru/                    # Русские сообщения валидации
│   ├── routes/
│   │   └── api.php                 # 9 endpoints
│   ├── public/
│   │   └── swagger.json            # OpenAPI 3.0 спецификация
│   ├── tests/Feature/              # ApiTest (16 тестов)
│   ├── docker-php-ext-opcache.ini  # OPcache для Docker
│   ├── Dockerfile
│   └── nginx.conf
│
├── frontend/                       # Nuxt 4 SPA
│   ├── app.vue                     # Корневой компонент
│   ├── nuxt.config.ts              # Конфигурация Nuxt
│   ├── assets/css/                 # main.css (глобальные стили, BEM, responsive)
│   ├── components/
│   │   ├── AppSelect.vue           # Кастомный селект с BEM
│   │   ├── ConfirmDialog.vue       # Модалка подтверждения
│   │   ├── ProfileForm.vue         # Редактирование профиля
│   │   ├── TaskFilters.vue         # Фильтры, сортировка, поиск
│   │   ├── TaskForm.vue            # Создание/редактирование задачи (модалка)
│   │   ├── TaskItem.vue            # Карточка задачи с статус-меню
│   │   └── TaskList.vue            # Список + скелетон + пустое состояние
│   ├── composables/
│   │   ├── useAuth.js              # Логин, логаут, обновление профиля
│   │   ├── useStickyButton.js      # Sticky-кнопка в шапке
│   │   └── useTasks.js             # CRUD задач
│   ├── layouts/
│   │   └── default.vue             # Шапка с пользователем + sticky-кнопка
│   ├── middleware/
│   │   └── auth.js                 # Route guard (редирект на /login)
│   ├── error.vue                   # Глобальная страница ошибки
│   ├── pages/
│   │   ├── [...slug].vue           # 404 catch-all
│   │   ├── dashboard.vue           # Главная страница с задачами
│   │   ├── index.vue               # Редирект на /login
│   │   └── login.vue               # Страница входа
│   ├── public/
│   │   ├── favicon.svg             # Фавиконка
│   │   └── icons/icon.svg          # Иконка
│   ├── tests/                      # 16 тестов
│   │   ├── auth.test.ts
│   │   ├── task-form.test.ts
│   │   └── task-item.test.ts
│   └── Dockerfile
│
├── docker-compose.yml
└── README.md
```

---

## Тестирование

### Backend (16 тестов)

```bash
cd backend
php artisan test
```

Проверяет: логин, CRUD задач, права доступа (owner/admin), валидацию, 401 без токена.

### Frontend (16 тестов)

```bash
cd frontend
npm run test
```

Проверяет: TaskForm (режимы, валидация, эмиты), TaskItem (отображение, права доступа), Auth (localStorage).

---

## Админ-панель (MoonShine)

На проекте доступна админ-панель на базе **MoonShine**.

- **URL**: http://localhost:8000/admin
- **Язык интерфейса**: русский
- **Управление**: пользователи (приложения), задачи, администраторы, роли

MoonShine использует отдельную таблицу администраторов `moonshine_users`, не связанную с обычными пользователями приложения.

### Установка ассетов MoonShine

Ассеты MoonShine (`public/vendor/`, `lang/vendor/`) не хранятся в Git. После `composer install` нужно опубликовать их:

```bash
cd backend
php artisan vendor:publish --provider="MoonShine\Laravel\Providers\MoonShineServiceProvider" --force
```

### Создание администратора

```bash
cd backend
php artisan moonshine:user
```

Команда интерактивно запросит email, имя и пароль. После создания вы сможете войти в админ-панель по адресу `/admin`.

---

## Дополнительные возможности

### Реализовано

- [x] Разделение ролей admin/user
- [x] Скрытие кнопок редактирования/удаления при отсутствии прав
- [x] Поиск с debounce (400ms) + синхронизация с query-параметрами URL
- [x] Пагинация (backend + frontend, скрытие при 1 странице)
- [x] Feature-тесты API (16 тестов)
- [x] Компонентные frontend-тесты (16 тестов)
- [x] OpenAPI 3.0 спецификация + Swagger UI
- [x] Docker Compose
- [x] Адаптив (4 брейкпоинта: 360–768–1024–1640–1920)
- [x] BEM-методология в CSS
- [x] Редактирование профиля (имя, email, пароль)
- [x] Цветные плашки дедлайна (красный/жёлтый/зелёный)
- [x] Показ дней до дедлайна
- [x] Кастомные скроллы
- [x] Русские сообщения валидации и ошибок API
- [x] Анимации (модалки, тултипы статуса, hover-эффекты)
- [x] Админ-панель MoonShine (управление пользователями, задачами, администраторами)



---

<div align="center">
  <br>
  <p>Разработано в рамках тестового задания</p>
  <br>
</div>
