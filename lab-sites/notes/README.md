# Лабораторный сайт «Заметки» (WEB, средний уровень)

Статический мини-сайт для заданий Кибер-Лаб. **Не зависит от Laravel** — заливается на Beget отдельно.

## Куда залить на Beget

1. Создайте поддомен, например `notes.ccyberlabs.ru` или папку `public_html/notes/`.
2. Скопируйте **всё содержимое** этой папки в корень сайта.
3. Откройте в браузере — должна открыться страница со списком заметок.

## URL в основном приложении

В `.env` основного проекта Кибер-Лаб:

```env
WEB_LAB_NOTES_URL=https://notes.ccyberlabs.ru
```

Затем на сервере:

```bash
php8.4 artisan db:seed --class=WebMediumTasksSeeder
```

## Где спрятаны флаги (для преподавателя)

| Задание | Где искать | Ответ |
|---------|------------|--------|
| 1. Заметки | `note-important.html` → исходный код, HTML-комментарий | `flag{notes_vault}` |
| 2. localStorage | F12 → Application → Local Storage → ключ `cyberlab_draft` | `flag{offline_draft}` |
| 3. Cookie | F12 → Application → Cookies | `flag{cookie_trail}` |
| 4. Скрытый текст | `note-archive.html` → исходный код или Inspect, класс `.secret-ink` | `flag{hidden_ink}` |

Cookie и localStorage выставляются скриптом `assets/app.js` на главной странице.
