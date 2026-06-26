<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

use App\Models\Topic;
use App\Models\Level;
use App\Models\TaskSubmission;
use App\Services\AchievementService;
use App\Services\UserRatingService;
use App\Support\PublicImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TopicController extends Controller
{
    private function getUserData()
    {
        $user = Auth::user();
        $role = $user->role ?? 'user';

        $roleLabel = match ($role) {
            'admin' => 'Администратор',
            'expert' => 'Эксперт',
            default => 'Пользователь',
        };

        return [
            "name" => $user->name,
            "role" => $roleLabel,
            "avatar" => PublicImage::avatar($user),
            "stats" => DB::table('user_stats')->get()->map(function ($item) {
                return (array) $item;
            })->toArray()
        ];
    }

    public function index(UserRatingService $rating)
    {
        $progressByTopic = collect($rating->allTopicsLevelProgressForUser(Auth::user()))
            ->keyBy('topic_id');

        $topics = Topic::query()
            ->orderBy('id')
            ->get()
            ->map(function (Topic $topic) use ($progressByTopic) {
                $progress = $progressByTopic->get($topic->id);

                return [
                    'id' => $topic->id,
                    'title' => $topic->title,
                    'image' => PublicImage::topic($topic),
                    'progress_current' => $progress['completed'] ?? 0,
                    'progress_total' => $progress['total'] ?? 0,
                    'progress_percent' => $progress['percent'] ?? 0,
                ];
            })
            ->values()
            ->all();

        return view('home', ['topics' => $topics, 'user' => $this->getUserData()]);
    }

    public function profile(AchievementService $achievements, UserRatingService $rating)
    {
        $authUser = Auth::user();
        $activitySummary = $rating->activitySummary($authUser);

        return view('profile', [
            'user' => $this->getUserData(),
            'profileStats' => [
                'daysOnPlatform' => $rating->daysOnPlatform($authUser),
                'rank' => $rating->rankForUser($authUser),
                'acceptedCount' => $rating->acceptedCount($authUser),
                'totalTasks' => $rating->totalTasksCount(),
                'lastActivityAt' => $activitySummary['last_solution_at'],
                'topicProgress' => $rating->topicProgressForUser($authUser),
            ],
            'activity' => [
                'summary' => $activitySummary,
                'history' => $rating->activityHistory($authUser),
            ],
            'achievementGroups' => $achievements->forUser($authUser),
        ]);
    }

    public function showTopic($id, UserRatingService $rating)
    {
        $topicModel = Topic::with('levels')->findOrFail($id);
        $user = Auth::user();

        $topic = $topicModel->toArray();
        $topic['image'] = PublicImage::topic($topicModel);
        $topic['levels'] = $topicModel->levels
            ->map(function (Level $level) use ($rating, $user) {
                $taskProgress = $rating->levelTaskProgressForUser($user, $level);
                $item = $level->toArray();
                $item['image'] = PublicImage::level($level);
                $item['progress_current'] = $taskProgress['accepted'];
                $item['progress_total'] = $taskProgress['total'];
                $item['progress_percent'] = $taskProgress['percent'];

                return $item;
            })
            ->values()
            ->all();

        return view('topic', ['topic' => $topic, 'user' => $this->getUserData()]);
    }

    public function showLevel($id)
    {
        $level = Level::with([
            'topic', 
            'themes.tasks.submissions' => fn($q) => $q->where('user_id', Auth::id()), 
            'tasks.submissions' => fn($q) => $q->where('user_id', Auth::id())
        ])->findOrFail($id);

        $selectedThemeId = (int) request('theme_id', 0);
        $selectedTheme = $level->themes->firstWhere('id', $selectedThemeId);
        if (! $selectedTheme) {
            $selectedTheme = $level->themes->first();
        }

        $tasks = $selectedTheme ? $selectedTheme->tasks : $level->tasks;

        // Получаем текущее задание из параметров или первое по порядку
        $taskId = request('task_id');
        $currentTask = $taskId
            ? $tasks->firstWhere('id', (int) $taskId)
            : $tasks->first();

        $submission = null;
        if ($currentTask) {
            $submission = $currentTask->submissions->first();
        }

        return view('level', [
            'level' => $level, 
            'currentTask' => $currentTask,
            'submission' => $submission,
            'selectedTheme' => $selectedTheme,
            'tasks' => $tasks,
            'user' => $this->getUserData()
        ]);
    }
}
