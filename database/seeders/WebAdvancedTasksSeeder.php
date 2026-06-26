<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\LevelTheme;
use App\Models\Task;
use Illuminate\Database\Seeder;

class WebAdvancedTasksSeeder extends Seeder
{
    public function run(): void
    {
        $webAdvancedLevel = Level::query()
            ->where('title', 'Продвинутый уровень')
            ->whereHas('topic', fn ($q) => $q->where('title', 'WEB'))
            ->first();

        if (! $webAdvancedLevel) {
            $this->command?->error('Продвинутый уровень «WEB» не найден. Сначала выполните DatabaseSeeder.');

            return;
        }

        $labUrl = config('cyberlab.web_lab_portal_url');

        $theme = LevelTheme::updateOrCreate(
            ['level_id' => $webAdvancedLevel->id, 'title' => 'Лабораторный портал'],
            ['sort_order' => 1]
        );

        $tasks = [
            [
                'title' => 'Задание 1. Авторизация 2.0',
                'content' => <<<HTML
<p>Разведка обнаружила учебный портал сотрудников:</p>
<p><a href="{$labUrl}" target="_blank" rel="noopener">{$labUrl}</a></p>
<p><strong>Цель:</strong> получить права администратора и найти флаг на защищённой странице <code>/admin.php</code>.</p>
<p>Зарегистрируйтесь и войдите. Прямой доступ к панели администратора для обычного пользователя закрыт.</p>
<p>Подумайте, <strong>где браузер хранит сведения о вашей роли</strong> после входа (F12 → Application → Cookies).</p>
<p>Ответ в формате <code>flag{...}</code>.</p>
HTML,
                'correct_answer' => 'flag{admin_cookie}',
                'order' => 1,
            ],
        ];

        foreach ($tasks as $taskData) {
            Task::updateOrCreate(
                [
                    'level_id' => $webAdvancedLevel->id,
                    'title' => $taskData['title'],
                ],
                array_merge($taskData, ['level_theme_id' => $theme->id])
            );
        }

        $this->command?->info('WEB продвинутый уровень: лабораторный портал, 1 задание.');
        $this->command?->info('URL портала: '.$labUrl);
    }
}
