<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\TaskSubmission;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Support\Collection;

class AchievementService
{
    public function solvedCount(User $user, ?int $topicId = null): int
    {
        $query = TaskSubmission::query()
            ->where('user_id', $user->id)
            ->where('status', 'accepted');

        if ($topicId !== null) {
            $query->whereHas('task.level', fn ($q) => $q->where('topic_id', $topicId));
        }

        return $query->count();
    }

    /**
     * @return array{global: array{earned: int, total: int, items: list<array>}, topics: list<array>}
     */
    public function forUser(User $user): array
    {
        $globalAchievements = Achievement::query()
            ->whereNull('topic_id')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $globalSolved = $this->solvedCount($user);
        $globalItems = $this->mapItems($globalAchievements, $globalSolved, null);

        $topicsWithAchievements = Topic::query()
            ->whereHas('achievements')
            ->with(['achievements' => fn ($q) => $q->orderBy('sort_order')->orderBy('id')])
            ->orderBy('id')
            ->get();

        $topicGroups = [];
        foreach ($topicsWithAchievements as $topic) {
            $solved = $this->solvedCount($user, $topic->id);
            $items = $this->mapItems($topic->achievements, $solved, $topic->id);
            $topicGroups[] = [
                'topic' => $topic,
                'earned' => collect($items)->where('earned', true)->count(),
                'total' => count($items),
                'items' => $items,
            ];
        }

        return [
            'global' => [
                'earned' => collect($globalItems)->where('earned', true)->count(),
                'total' => count($globalItems),
                'items' => $globalItems,
            ],
            'topics' => $topicGroups,
        ];
    }

    /**
     * @return list<string>
     */
    public function earnedTitlesForUser(User $user, int $limit = 10): array
    {
        $groups = $this->forUser($user);
        $titles = [];

        foreach ($groups['global']['items'] as $item) {
            if ($item['earned']) {
                $titles[] = $item['title'];
            }
        }

        foreach ($groups['topics'] as $group) {
            foreach ($group['items'] as $item) {
                if ($item['earned']) {
                    $titles[] = $item['title'];
                }
            }
        }

        if (empty($titles)) {
            return ['На старте пути'];
        }

        return array_slice($titles, 0, $limit);
    }

    public function earnedCountForUser(User $user): int
    {
        $groups = $this->forUser($user);

        return $groups['global']['earned'] + collect($groups['topics'])->sum('earned');
    }

    /**
     * @param  Collection<int, Achievement>  $achievements
     * @return list<array<string, mixed>>
     */
    private function mapItems(Collection $achievements, int $solvedCount, ?int $topicId): array
    {
        $tasksUrl = $topicId
            ? route('topic.show', ['id' => $topicId])
            : route('home');

        return $achievements->map(function (Achievement $achievement) use ($solvedCount, $tasksUrl, $topicId) {
            $earned = $solvedCount >= $achievement->threshold;

            return [
                'id' => $achievement->id,
                'title' => $achievement->title,
                'description' => $achievement->description,
                'icon' => $achievement->icon,
                'threshold' => $achievement->threshold,
                'earned' => $earned,
                'tasks_url' => $tasksUrl,
                'in_category' => $topicId !== null,
            ];
        })->values()->all();
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     */
    public function syncTopicAchievements(Topic $topic, array $rows): void
    {
        foreach ($rows as $index => $row) {
            if (empty($row['title'] ?? null)) {
                continue;
            }

            Achievement::create([
                'topic_id' => $topic->id,
                'title' => $row['title'],
                'description' => $row['description'] ?? '',
                'icon' => $row['icon'] ?? '',
                'threshold' => (int) ($row['threshold'] ?? 1),
                'sort_order' => (int) ($row['sort_order'] ?? $index),
            ]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function achievementRules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'icon' => ['required', 'string', 'max:2048'],
            'threshold' => ['required', 'integer', 'min:1'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
