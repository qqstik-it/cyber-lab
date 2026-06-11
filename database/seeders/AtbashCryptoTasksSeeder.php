<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\LevelTheme;
use App\Models\Task;
use Illuminate\Database\Seeder;

class AtbashCryptoTasksSeeder extends Seeder
{
    public function run(): void
    {
        $cryptoBaseLevel = Level::query()
            ->where('title', 'Базовый уровень')
            ->whereHas('topic', fn ($q) => $q->where('title', 'Криптография'))
            ->first();

        if (! $cryptoBaseLevel) {
            $this->command?->error('Базовый уровень «Криптография» не найден.');

            return;
        }

        $atbashTheme = LevelTheme::updateOrCreate(
            ['level_id' => $cryptoBaseLevel->id, 'title' => 'Шифр Атбаш'],
            ['sort_order' => 2]
        );

        LevelTheme::query()
            ->where('level_id', $cryptoBaseLevel->id)
            ->where('title', 'Base64')
            ->update(['sort_order' => 3]);

        $tasks = [
            [
                'title' => 'Задание 4. Атбаш: первый флаг',
                'content' => '<p>Перехвачено сообщение: <code>uozt{xibkgl_yzhrx}</code>. Известно, что использован шифр Атбаш — каждая буква заменена на симметричную в латинском алфавите (a↔z, b↔y, c↔x и т.д.). Расшифруйте сообщение и найдите флаг.</p>',
                'correct_answer' => 'flag{crypto_basic}',
                'order' => 4,
            ],
            [
                'title' => 'Задание 5. Атбаш: тайное сообщение',
                'content' => '<p>Сообщение зашифровано шифром Атбаш: <code>uozt{zgyzhs_hxsvnv}</code>. Восстановите исходный флаг.</p>',
                'correct_answer' => 'flag{atbash_scheme}',
                'order' => 5,
            ],
            [
                'title' => 'Задание 6. Атбаш: без лишнего',
                'content' => '<p>Получена строка: <code>uozt{hvxfiv_nvhhztv}</code>. Каждая буква заменена на симметричную по алфавиту (шифр Атбаш). Расшифруйте сообщение.</p>',
                'correct_answer' => 'flag{secure_message}',
                'order' => 6,
            ],
        ];

        foreach ($tasks as $taskData) {
            Task::updateOrCreate(
                [
                    'level_id' => $cryptoBaseLevel->id,
                    'title' => $taskData['title'],
                ],
                array_merge($taskData, ['level_theme_id' => $atbashTheme->id])
            );
        }

        Task::query()
            ->where('level_id', $cryptoBaseLevel->id)
            ->where('title', 'Задание 4. Один Base64')
            ->update(['title' => 'Задание 7. Один Base64', 'order' => 7]);

        Task::query()
            ->where('level_id', $cryptoBaseLevel->id)
            ->where('title', 'Задание 5. Двойной Base64')
            ->update(['title' => 'Задание 8. Двойной Base64', 'order' => 8]);

        $base64Theme = LevelTheme::query()
            ->where('level_id', $cryptoBaseLevel->id)
            ->where('title', 'Base64')
            ->first();

        if ($base64Theme) {
            Task::query()
                ->where('level_id', $cryptoBaseLevel->id)
                ->whereIn('title', ['Задание 7. Один Base64', 'Задание 8. Двойной Base64'])
                ->update(['level_theme_id' => $base64Theme->id]);
        }

        $this->command?->info('Подтема «Шифр Атбаш» и 3 задания добавлены. Base64 перенумерованы в 7–8.');
    }
}
