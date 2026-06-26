# Лабораторный сайт «Портал» (WEB, продвинутый уровень)

Учебный мини-сайт с формой входа и уязвимостью: **роль пользователя хранится в cookie** и проверяется на странице `/admin.php` без сверки с сервером.

## Куда залить на Beget

1. Создайте поддомен `portal.ccyberlabs.ru` (или папку в `public_html`).
2. Скопируйте **всё содержимое** этой папки в корень сайта.
3. Убедитесь, что для сайта выбран **PHP 8.4** (Сайты → шестерёнка → PHP) или залит файл `.htaccess` из проекта.
4. Папка `data/` доступна для записи (права 755).

## URL в основном приложении

В `.env` проекта Кибер-Лаб:

```env
WEB_LAB_PORTAL_URL=https://portal.ccyberlabs.ru
```

Затем:

```bash
php8.4 artisan db:seed --class=WebAdvancedTasksSeeder
```

## Решение (для преподавателя)

1. Зарегистрироваться и войти на портал.
2. F12 → **Application** → **Cookies** → домен портала.
3. Cookie `role`: значение `user` → изменить на `admin`.
4. Открыть `/admin.php` → флаг `flag{admin_cookie}`.

## Локальный запуск (OSPanel)

Создайте домен `portal.local` в OSPanel, укажите корень на эту папку, или:

```bash
cd lab-sites/portal
php -S localhost:8090
```

Откройте http://localhost:8090 и в `.env` укажите `WEB_LAB_PORTAL_URL=http://localhost:8090`.
