<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

use App\Models\Topic;
use App\Models\Level;
use App\Models\TaskSubmission;
use App\Services\AchievementService;
use App\Services\UserRatingService;
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
            "avatar" => route('avatar.proxy', ['user' => $user->id]),
            "stats" => DB::table('user_stats')->get()->map(function ($item) {
                return (array) $item;
            })->toArray()
        ];
    }

    public function index()
    {
        $topics = Topic::all()
            ->map(function ($topic) {
                $item = $topic->toArray();
                $item['image'] = route('topic.image.proxy', ['topic' => $topic->id]);
                return $item;
            })
            ->toArray();
        return view('home', ['topics' => $topics, 'user' => $this->getUserData()]);
    }

    public function login()
    {
        return view('login');
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

    public function showTopic($id)
    {
        $topic = Topic::with('levels')->findOrFail($id)->toArray();
        $topic['image'] = route('topic.image.proxy', ['topic' => $id]);
        $topic['levels'] = collect($topic['levels'])
            ->map(function ($level) {
                $level['image'] = route('level.image.proxy', ['level' => $level['id']]);
                return $level;
            })
            ->toArray();

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
