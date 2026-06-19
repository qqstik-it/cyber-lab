<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\LevelTheme;
use App\Models\Task;
use Illuminate\Database\Seeder;

class WebBasicTasksSeeder extends Seeder
{
    public function run(): void
    {
        $webBaseLevel = Level::query()
            ->where('title', 'Базовый уровень')
            ->whereHas('topic', fn ($q) => $q->where('title', 'WEB'))
            ->first();

        if (! $webBaseLevel) {
            $this->command?->error('Базовый уровень «WEB» не найден. Сначала выполните DatabaseSeeder.');

            return;
        }

        Task::query()
            ->where('level_id', $webBaseLevel->id)
            ->whereIn('title', [
                'Базовая структура HTML',
                'Ссылки и изображения',
            ])
            ->delete();

        $themes = [
            1 => 'Структура HTML',
            2 => 'Атрибуты',
            3 => 'Скрытое в коде',
        ];

        foreach ($themes as $sortOrder => $themeTitle) {
            LevelTheme::updateOrCreate(
                ['level_id' => $webBaseLevel->id, 'title' => $themeTitle],
                ['sort_order' => $sortOrder]
            );
        }

        $themeByTitle = LevelTheme::query()
            ->where('level_id', $webBaseLevel->id)
            ->pluck('id', 'title');

        $tasks = [
            [
                'theme' => 'Структура HTML',
                'title' => 'Задание 1. Комментарий в коде',
                'content' => <<<'HTML'
<p>Аналитик сообщает: на странице этого задания оставлен служебный HTML-комментарий рядом с формой отправки ответа.</p>
<p>Откройте <strong>исходный код страницы</strong> (Ctrl+U) или инструменты разработчика (F12) и найдите флаг в комментарии.</p>
<p>Ответ в формате <code>flag{...}</code>.</p>
HTML,
                'correct_answer' => 'flag{view_source}',
                'order' => 1,
            ],
            [
                'theme' => 'Структура HTML',
                'title' => 'Задание 2. Семантический тег',
                'content' => <<<'HTML'
<p>На странице задания есть фрагмент разметки с главным заголовком платформы. Он не отображается в интерфейсе, но присутствует в DOM.</p>
<p>Изучите исходный код страницы и определите, какой HTML-тег используется для главного заголовка.</p>
<p>Ответ укажите в формате <code>flag{название_тега}</code>, например <code>flag{div}</code>.</p>
HTML,
                'correct_answer' => 'flag{h1}',
                'order' => 2,
            ],
            [
                'theme' => 'Атрибуты',
                'title' => 'Задание 3. Подозрительная ссылка',
                'content' => <<<'HTML'
<p>На странице задания есть ссылка «Вход». Проверьте её разметку в исходном коде — аналитик оставил метку в атрибуте <code>data-secret</code>.</p>
<p>Найдите значение атрибута <code>data-secret</code> и отправьте его как ответ.</p>
HTML,
                'correct_answer' => 'flag{check_href}',
                'order' => 3,
            ],
            [
                'theme' => 'Атрибуты',
                'title' => 'Задание 4. Alt у изображения',
                'content' => <<<'HTML'
<p>Разработчик добавил на страницу служебное изображение. Оно скрыто от пользователя, но остаётся в HTML.</p>
<p>Найдите в исходном коде тег <code>&lt;img&gt;</code> и прочитайте значение атрибута <code>alt</code>.</p>
<p>Ответ в формате <code>flag{...}</code>.</p>
HTML,
                'correct_answer' => 'flag{alt_text}',
                'order' => 4,
            ],
            [
                'theme' => 'Скрытое в коде',
                'title' => 'Задание 5. HTML-сущности',
                'content' => <<<'HTML'
<p>В исходном коде страницы есть HTML-комментарий с текстом, закодированным через decimal-сущности вида <code>&amp;#102;</code>.</p>
<p>Каждая последовательность <code>&amp;#NNN;</code> — это код символа. Восстановите строку и найдите флаг.</p>
<p>Ответ в формате <code>flag{...}</code>.</p>
HTML,
                'correct_answer' => 'flag{entities}',
                'order' => 5,
            ],
            [
                'theme' => 'Скрытое в коде',
                'title' => 'Задание 6. Скрытое поле формы',
                'content' => <<<'HTML'
<p>На странице спрятана декоративная форма авторизации — она не видна на экране, но есть в HTML.</p>
<p>Найдите в исходном коде поле <code>&lt;input type="hidden"&gt;</code> и прочитайте его атрибут <code>value</code>.</p>
<p>Ответ в формате <code>flag{...}</code>.</p>
HTML,
                'correct_answer' => 'flag{hidden_field}',
                'order' => 6,
            ],
        ];

        foreach ($tasks as $taskData) {
            $themeId = $themeByTitle[$taskData['theme']] ?? null;
            unset($taskData['theme']);

            Task::updateOrCreate(
                [
                    'level_id' => $webBaseLevel->id,
                    'title' => $taskData['title'],
                ],
                array_merge($taskData, ['level_theme_id' => $themeId])
            );
        }

        $this->command?->info('WEB базовый уровень: 3 подтемы и 6 заданий добавлены.');
        $this->command?->info('Старые задания «Базовая структура HTML» и «Ссылки и изображения» удалены.');
    }
}
