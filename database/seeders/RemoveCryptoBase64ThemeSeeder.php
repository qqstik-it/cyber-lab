<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\LevelTheme;
use App\Models\Task;
use Illuminate\Database\Seeder;

class RemoveCryptoBase64ThemeSeeder extends Seeder
{
    private const BASE64_TASK_TITLES = [
        'Задание 7. Один Base64',
        'Задание 8. Двойной Base64',
        'Задание 4. Один Base64',
        'Задание 5. Двойной Base64',
    ];

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

        $deletedTasks = Task::query()
            ->where('level_id', $cryptoBaseLevel->id)
            ->whereIn('title', self::BASE64_TASK_TITLES)
            ->delete();

        $deletedTheme = LevelTheme::query()
            ->where('level_id', $cryptoBaseLevel->id)
            ->where('title', 'Base64')
            ->delete();

        $this->command?->info("Удалено заданий Base64: {$deletedTasks}.");
        $this->command?->info($deletedTheme ? 'Подтема «Base64» удалена.' : 'Подтема «Base64» не найдена.');
    }
}
