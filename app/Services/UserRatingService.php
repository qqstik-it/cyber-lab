<?php

namespace App\Services;

use App\Models\Level;
use App\Models\Task;
use App\Models\TaskSubmission;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class UserRatingService
{
    private const TOP_LEADER_EMAIL = 'test@test.ru';

    public function __construct(
        private AchievementService $achievements
    ) {}

    public function acceptedCount(User $user): int
    {
        return $this->achievements->solvedCount($user);
    }

    public function totalTasksCount(): int
    {
        return Task::query()->count();
    }

    public function rankForUser(User $user): ?int
    {
        $accepted = $this->acceptedCount($user);
        if ($accepted === 0) {
            return null;
        }

        $ahead = DB::table('users')
            ->joinSub(
                TaskSubmission::query()
                    ->select('user_id', DB::raw('COUNT(*) as accepted_count'))
                    ->where('status', 'accepted')
                    ->groupBy('user_id'),
                'stats',
                'stats.user_id',
                '=',
                'users.id'
            )
            ->where(function ($query) {
                $query->whereNull('users.role')
                    ->orWhere('users.role', '!=', 'admin');
            })
            ->where('stats.accepted_count', '>', $accepted)
            ->count();

        return $ahead + 1;
    }

    /**
     * @return list<array{title: string, accepted: int, total: int, percent: int, caption: string, topic_id: int}>
     */
    public function topicProgressForUser(User $user): array
    {
        $topics = Topic::query()->orderBy('id')->get();
        $result = [];

        foreach ($topics as $topic) {
            $total = Task::query()
                ->whereHas('level', fn ($q) => $q->where('topic_id', $topic->id))
                ->count();

            $accepted = $this->achievements->solvedCount($user, $topic->id);
            $percent = $total > 0 ? (int) round(min(100, ($accepted / $total) * 100)) : 0;

            $caption = match (true) {
                $total === 0 => 'В теме пока нет заданий',
                $accepted === 0 => 'Пока нет принятых заданий',
                $accepted >= $total => 'Все задания темы приняты',
                default => "{$percent}% темы",
            };

            $result[] = [
                'topic_id' => $topic->id,
                'title' => $topic->title,
                'accepted' => $accepted,
                'total' => $total,
                'percent' => $percent,
                'caption' => $caption,
            ];
        }

        return $result;
    }

    /**
     * Прогресс по заданиям внутри одного уровня.
     *
     * @return array{accepted: int, total: int, percent: int, completed: bool}
     */
    public function levelTaskProgressForUser(User $user, Level|int $level): array
    {
        $levelId = $level instanceof Level ? $level->id : $level;

        $total = Task::query()->where('level_id', $levelId)->count();

        $accepted = TaskSubmission::query()
            ->where('user_id', $user->id)
            ->where('status', 'accepted')
            ->whereHas('task', fn ($q) => $q->where('level_id', $levelId))
            ->count();

        $percent = $total > 0 ? (int) round(min(100, ($accepted / $total) * 100)) : 0;
        $completed = $total > 0 && $accepted >= $total;

        return [
            'accepted' => $accepted,
            'total' => $total,
            'percent' => $percent,
            'completed' => $completed,
        ];
    }

    /**
     * Прогресс по уровням внутри темы (уровень пройден, если все его задания приняты).
     *
     * @return array{completed: int, total: int, percent: int}
     */
    public function topicLevelProgressForUser(User $user, Topic|int $topic): array
    {
        $topicModel = $topic instanceof Topic
            ? $topic->loadMissing('levels')
            : Topic::query()->with('levels')->findOrFail($topic);

        $total = $topicModel->levels->count();
        $completed = 0;

        foreach ($topicModel->levels as $level) {
            if ($this->levelTaskProgressForUser($user, $level)['completed']) {
                $completed++;
            }
        }

        $percent = $total > 0 ? (int) round(min(100, ($completed / $total) * 100)) : 0;

        return [
            'completed' => $completed,
            'total' => $total,
            'percent' => $percent,
        ];
    }

    /**
     * @return list<array{topic_id: int, title: string, completed: int, total: int, percent: int}>
     */
    public function allTopicsLevelProgressForUser(User $user): array
    {
        $topics = Topic::query()->with('levels')->orderBy('id')->get();
        $result = [];

        foreach ($topics as $topic) {
            $progress = $this->topicLevelProgressForUser($user, $topic);

            $result[] = [
                'topic_id' => $topic->id,
                'title' => $topic->title,
                'completed' => $progress['completed'],
                'total' => $progress['total'],
                'percent' => $progress['percent'],
            ];
        }

        return $result;
    }

    /**
     * @return array{
     *     total: int,
     *     last_30_days: int,
     *     categories_with_solutions: int,
     *     last_solution_at: ?Carbon
     * }
     */
    public function activitySummary(User $user): array
    {
        $acceptedQuery = TaskSubmission::query()
            ->where('user_id', $user->id)
            ->where('status', 'accepted');

        $total = (clone $acceptedQuery)->count();

        $last30Days = (clone $acceptedQuery)
            ->where('updated_at', '>=', now()->subDays(30))
            ->count();

        $categoriesWithSolutions = (clone $acceptedQuery)
            ->whereHas('task.level')
            ->with('task.level')
            ->get()
            ->pluck('task.level.topic_id')
            ->filter()
            ->unique()
            ->count();

        $lastSubmission = (clone $acceptedQuery)
            ->orderByDesc(DB::raw('COALESCE(reviewed_at, updated_at)'))
            ->first();

        $lastAt = null;
        if ($lastSubmission) {
            $lastAt = $lastSubmission->reviewed_at ?? $lastSubmission->updated_at;
        }

        return [
            'total' => $total,
            'last_30_days' => $last30Days,
            'categories_with_solutions' => $categoriesWithSolutions,
            'last_solution_at' => $lastAt,
        ];
    }

    /**
     * @return Collection<int, TaskSubmission>
     */
    public function activityHistory(User $user, int $limit = 50): Collection
    {
        return TaskSubmission::query()
            ->with(['task.level.topic'])
            ->where('user_id', $user->id)
            ->where('status', 'accepted')
            ->orderByDesc(DB::raw('COALESCE(reviewed_at, updated_at)'))
            ->limit($limit)
            ->get();
    }

    public function daysOnPlatform(User $user): int
    {
        return (int) $user->created_at?->diffInDays(now()) ?? 0;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function topUsers(int $limit = 3): array
    {
        $totalTasks = $this->totalTasksCount();

        $rows = $this->rankedUsersQuery()
            ->get()
            ->filter(fn ($row) => (int) $row->accepted_count > 0)
            ->sortBy(fn ($row) => [
                -(int) $row->accepted_count,
                ($row->email ?? '') === self::TOP_LEADER_EMAIL ? 0 : 1,
                $row->name,
            ])
            ->take($limit)
            ->values();

        return $rows->map(function ($row) use ($totalTasks) {
            $user = User::find($row->id);
            $accepted = (int) $row->accepted_count;
            $submitted = (int) $row->submitted_count;

            $progress = $totalTasks > 0
                ? (int) round(min(100, ($accepted / $totalTasks) * 100))
                : 0;

            $completedTasks = DB::table('task_submissions as ts')
                ->join('tasks', 'tasks.id', '=', 'ts.task_id')
                ->leftJoin('levels', 'levels.id', '=', 'tasks.level_id')
                ->leftJoin('topics', 'topics.id', '=', 'levels.topic_id')
                ->where('ts.user_id', $row->id)
                ->where('ts.status', 'accepted')
                ->orderByDesc('ts.updated_at')
                ->limit(3)
                ->get([
                    'tasks.title as task_title',
                    'topics.title as topic_title',
                ])
                ->map(fn ($item) => [
                    'task' => $item->task_title,
                    'topic' => $item->topic_title ?: 'Без темы',
                ])
                ->toArray();

            $awards = $user
                ? $this->achievements->earnedTitlesForUser($user)
                : ['На старте пути'];

            return [
                'id' => (int) $row->id,
                'name' => $row->name,
                'avatar' => route('avatar.proxy', ['user' => $row->id]),
                'progress' => $progress,
                'accepted_count' => $accepted,
                'submitted_count' => $submitted,
                'awards_count' => $user ? $this->achievements->earnedCountForUser($user) : count($awards),
                'completed_tasks' => $completedTasks,
                'awards' => $awards,
            ];
        })->values()->all();
    }

    private function rankedUsersQuery()
    {
        return DB::table('users')
            ->leftJoin('task_submissions as ts', 'ts.user_id', '=', 'users.id')
            ->where(function ($query) {
                $query->whereNull('users.role')
                    ->orWhere('users.role', '!=', 'admin');
            })
            ->groupBy('users.id', 'users.name', 'users.email', 'users.avatar')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.avatar',
                DB::raw("SUM(CASE WHEN ts.status = 'accepted' THEN 1 ELSE 0 END) as accepted_count"),
                DB::raw('COUNT(ts.id) as submitted_count')
            );
    }
}
