<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\LevelTheme;
use App\Models\Task;
use Illuminate\Database\Seeder;

class WebMediumTasksSeeder extends Seeder
{
    public function run(): void
    {
        $webMiddleLevel = Level::query()
            ->where('title', 'Средний уровень')
            ->whereHas('topic', fn ($q) => $q->where('title', 'WEB'))
            ->first();

        if (! $webMiddleLevel) {
            $this->command?->error('Средний уровень «WEB» не найден. Сначала выполните DatabaseSeeder.');

            return;
        }

        $labUrl = config('cyberlab.web_lab_notes_url');

        $theme = LevelTheme::updateOrCreate(
            ['level_id' => $webMiddleLevel->id, 'title' => 'Лабораторный сайт «Заметки»'],
            ['sort_order' => 1]
        );

        $tasks = [
            [
                'title' => 'Задание 1. Всё важное в заметках',
                'content' => <<<HTML
<p>Разведка обнаружила внешний сервис с личными заметками сотрудника:</p>
<p><a href="{$labUrl}" target="_blank" rel="noopener">{$labUrl}</a></p>
<p><strong>Единственная подсказка:</strong> всё самое важное я храню в заметках.</p>
<p>Найдите флаг и отправьте в формате <code>flag{...}</code>.</p>
HTML,
                'correct_answer' => 'flag{notes_vault}',
                'order' => 1,
            ],
            [
                'title' => 'Задание 2. Черновик в браузере',
                'content' => <<<HTML
<p>Тот же сайт заметок: <a href="{$labUrl}" target="_blank" rel="noopener">{$labUrl}</a></p>
<p>Инженер писал, что часть данных <strong>не уходит на сервер</strong>, а остаётся только в браузере пользователя.</p>
<p>Откройте инструменты разработчика (F12) → вкладка <strong>Application</strong> (Хранилище) → <strong>Local Storage</strong>.</p>
<p>Найдите флаг и отправьте ответ.</p>
HTML,
                'correct_answer' => 'flag{offline_draft}',
                'order' => 2,
            ],
            [
                'title' => 'Задание 3. След в cookie',
                'content' => <<<HTML
<p>Сайт: <a href="{$labUrl}" target="_blank" rel="noopener">{$labUrl}</a></p>
<p>После открытия главной страницы в браузере мог остаться технический след — проверьте <strong>Cookies</strong> для этого сайта (F12 → Application → Cookies).</p>
<p>Найдите значение, похожее на флаг, и отправьте его.</p>
HTML,
                'correct_answer' => 'flag{cookie_trail}',
                'order' => 3,
            ],
            [
                'title' => 'Задание 4. Невидимые чернила',
                'content' => <<<HTML
<p>Сайт: <a href="{$labUrl}" target="_blank" rel="noopener">{$labUrl}</a></p>
<p>В одной из «старых» заметок текст спрятан не комментарием, а прямо на странице — его не видно глазами, но он есть в HTML.</p>
<p>Изучите исходный код или инспектор элементов (F12).</p>
<p>Ответ в формате <code>flag{...}</code>.</p>
HTML,
                'correct_answer' => 'flag{hidden_ink}',
                'order' => 4,
            ],
        ];

        foreach ($tasks as $taskData) {
            Task::updateOrCreate(
                [
                    'level_id' => $webMiddleLevel->id,
                    'title' => $taskData['title'],
                ],
                array_merge($taskData, ['level_theme_id' => $theme->id])
            );
        }

        $this->command?->info('WEB средний уровень: лабораторный сайт «Заметки», 4 задания.');
        $this->command?->info('URL лаборатории: '.$labUrl);
    }
}
