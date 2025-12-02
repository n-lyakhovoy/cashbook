# Cashbook - Система учета наличных денег

Веб-приложение для ведения учета поступления и выдачи наличных денег с управлением сотрудниками, расчетом зарплаты и поддержкой двухфакторной аутентификации.

## Требования

- Docker и Docker Compose
- Git

## Быстрый старт

### 1. Запуск контейнеров

```bash
./vendor/bin/sail up -d
```

Это запустит:
- Laravel приложение на `http://localhost`
- MariaDB базу данных
- Redis кэш
- Mailpit (для тестирования email на `http://localhost:8025`)

### 2. Генерация миграций и сидов

```bash
./vendor/bin/sail artisan migrate:fresh --seed
```

Это создаст таблицы и загрузит тестовые данные.

## Учетные данные по умолчанию

После запуска `migrate:fresh --seed`:

### Супер-администратор
- Email: `superadmin@cashbook.local`
- Пароль: `password`

### Администратор (Чтение-Запись)
- Email: `admin@cashbook.local`
- Пароль: `password`

### Администратор (Только чтение)
- Email: `admin-read@cashbook.local`
- Пароль: `password`

## Основные функции

### 1. Управление поступлениями наличных
- Добавление записей о поступлении денег
- Отслеживание источника, суммы, даты и администратора
- Уведомления в реальном времени

Путь: `/cash`

### 2. Управление сотрудниками
- Добавление/редактирование сотрудников
- Указание отдела, должности, оклада, приоритета

Путь: `/employees`

### 3. Расчетные ведомости
- Месячные расчеты зарплаты
- Управление бонусами, штрафами, авансами
- Автоматический расчет "к выдаче наличными"
- Цветовое кодирование статуса

Путь: `/payroll`

### 4. Двухфакторная аутентификация
- Google Authenticator поддержка

## Таблицы БД

- **users** - Администраторы и пользователи
- **employees** - Сотрудники компании
- **cash_entries** - Поступления наличных
- **payouts** - Выплаты сотрудникам
- **payroll_monthlies** - Месячные расчеты
- **notifications** - Уведомления
- **settings** - Настройки пользователей

## Команды

```bash
# Миграции
./vendor/bin/sail artisan migrate

# Откат миграций
./vendor/bin/sail artisan migrate:rollback

# Переприменение + seeding
./vendor/bin/sail artisan migrate:fresh --seed

# Просмотр логов
./vendor/bin/sail logs -f laravel

# Горячая перезагрузка фронтенда
./vendor/bin/sail npm run dev

# Запуск тестов
./vendor/bin/sail artisan test
```

## Переменные окружения

Основная конфигурация в `.env`:

```
APP_NAME=Cashbook
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=mysql
DB_DATABASE=cashbook
DB_USERNAME=sail
DB_PASSWORD=password

MAIL_MAILER=mailpit
```

## Развертывание

1. Установите зависимости: `composer install`
2. Установите `APP_KEY`: `php artisan key:generate`
3. Запустите миграции: `php artisan migrate --force`
4. Установите права на папки: `storage` и `bootstrap/cache`
5. Настройте веб-сервер для папки `public`

## Лицензия

Для коммерческого использования требуется согласование.

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
