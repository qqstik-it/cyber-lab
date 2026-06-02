<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Task;
use App\Models\Topic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class AchievementSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Achievement::query()->delete();
        Schema::enableForeignKeyConstraints();

        $this->seedGlobalAchievements();
        $this->seedTopicAchievements();
    }

    private function seedGlobalAchievements(): void
    {
        $rows = [
            [
                'title' => 'Первый флаг',
                'description' => 'Любая сдача принята на платформе',
                'icon' => '🎯',
                'threshold' => 1,
                'sort_order' => 1,
            ],
            [
                'title' => 'Разгон',
                'description' => 'Три принятых задания в разных темах',
                'icon' => '⚡',
                'threshold' => 3,
                'sort_order' => 2,
            ],
            [
                'title' => 'Стабильный темп',
                'description' => 'Пять принятых сдач',
                'icon' => '📈',
                'threshold' => 5,
                'sort_order' => 3,
            ],
            [
                'title' => 'Кибер-практик',
                'description' => 'Семь принятых заданий — серьёзный прогресс',
                'icon' => '🛡️',
                'threshold' => 7,
                'sort_order' => 4,
            ],
            [
                'title' => 'Охотник за знаниями',
                'description' => 'Десять принятых сдач по всей платформе',
                'icon' => '🏅',
                'threshold' => 10,
                'sort_order' => 5,
            ],
        ];

        foreach ($rows as $row) {
            Achievement::create(array_merge($row, ['topic_id' => null]));
        }
    }

    private function seedTopicAchievements(): void
    {
        $topicFlair = [
            'Криптография' => ['🔐', '🔑', '🧩', '📜', '🏆'],
            'WEB' => ['🌐', '🧱', '🎨', '🔗', '🏆'],
            'Сетевой трафик' => ['📡', '📦', '🔍', '🛰️', '🏆'],
            'Аутентификация' => ['🔒', '🪪', '🛡️', '🔐', '🏆'],
        ];

        $titleStems = [
            'Новичок',
            'В процессе',
            'Уверенный уровень',
            'Почти эксперт',
            'Мастер темы',
        ];

        foreach (Topic::query()->orderBy('id')->get() as $topic) {
            $taskCount = Task::query()
                ->whereHas('level', fn ($q) => $q->where('topic_id', $topic->id))
                ->count();

            $thresholds = $this->thresholdsForTaskCount($taskCount);
            $icons = $topicFlair[$topic->title] ?? ['📘', '✏️', '📌', '⭐', '🏆'];

            foreach ($thresholds as $index => $threshold) {
                $stem = $titleStems[$index] ?? 'Достижение';
                $icon = $icons[$index] ?? '🏆';

                $description = $threshold === 1
                    ? "Первое принятое задание в теме «{$topic->title}»"
                    : "Принято заданий в теме «{$topic->title}»: {$threshold}";

                if ($taskCount > 0 && $threshold >= $taskCount) {
                    $description = "Все доступные задания в «{$topic->title}» приняты";
                    $stem = 'Тема закрыта';
                }

                Achievement::create([
                    'topic_id' => $topic->id,
                    'title' => "{$stem}: {$topic->title}",
                    'description' => $description,
                    'icon' => $icon,
                    'threshold' => $threshold,
                    'sort_order' => $index + 1,
                ]);
            }
        }
    }

    /**
     * @return list<int>
     */
    private function thresholdsForTaskCount(int $taskCount): array
    {
        if ($taskCount <= 0) {
            return [1];
        }

        $candidates = [1];

        if ($taskCount >= 2) {
            $candidates[] = 2;
        }

        if ($taskCount >= 3) {
            $candidates[] = (int) max(2, ceil($taskCount / 2));
        }

        if ($taskCount >= 4) {
            $candidates[] = (int) max(3, ceil($taskCount * 0.75));
        }

        $candidates[] = $taskCount;

        $thresholds = array_values(array_unique(array_filter(
            $candidates,
            fn (int $value) => $value >= 1 && $value <= $taskCount
        )));
        sort($thresholds);

        return $thresholds;
    }
}
