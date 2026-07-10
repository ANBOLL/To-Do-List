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
| **Backend** | Laravel 11, PHP 8.3+, Eloquent ORM |
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

# Запуск dev-сервера
npm run dev
```

Frontend будет доступен на **http://localhost:5173**.

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

```bash
# Запуск всех сервисов
docker compose up --build
```

- Backend: **http://localhost:8000**
- Frontend: **http://localhost:5173**

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
| `sort_by` | `due_date` / `status` | Сортировка |
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
| `401` | `{"message": "Не авторизован.", "errors": {...}}` |
| `403` | `{"message": "Доступ запрещён.", "errors": {...}}` |
| `404` | `{"message": "Не найдено.", "errors": {...}}` |
| `422` | `{"message": "Ошибка валидации.", "errors": {...}}` |
| `500` | `{"message": "Внутренняя ошибка сервера.", "errors": {...}}` |

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
│   │   └── Enums/                  # TaskStatus, UserRole
│   ├── config/                     # moonshine.php, auth.php, sanctum.php
│   ├── database/
│   │   ├── factories/              # UserFactory, TaskFactory
│   │   ├── migrations/             # users, tasks, personal_access_tokens
│   │   └── seeders/                # UserSeeder, TaskSeeder
│   ├── lang/ru/                    # Русские сообщения валидации
│   ├── routes/
│   │   └── api.php                 # 8 endpoints
│   ├── public/
│   │   └── swagger.json            # OpenAPI 3.0 спецификация
│   ├── tests/Feature/              # ApiTest (16 тестов)
│   └── Dockerfile
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
│   ├── pages/
│   │   ├── [...slug].vue           # 404 catch-all
│   │   ├── dashboard.vue           # Главная страница с задачами
│   │   ├── error.vue               # Глобальная страница ошибки
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
