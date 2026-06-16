<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskSubmissionController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminTopicController;
use App\Http\Controllers\Admin\AdminLevelController;
use App\Http\Controllers\Admin\AdminTaskController;
use App\Http\Controllers\Admin\AdminSubmissionController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminAchievementController;
use App\Services\UserRatingService;
use App\Models\User;
use App\Models\Topic;
use App\Models\Level;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

if (! function_exists('servePublicImage')) {
    function servePublicImage(?string $value, callable $fallback)
    {
        $value = trim((string) ($value ?? ''));
        if ($value !== '' && ! preg_match('#^https?://#i', $value)) {
            $localPath = public_path(ltrim($value, '/'));
            if (is_file($localPath)) {
                return response()->file($localPath, ['Cache-Control' => 'public, max-age=3600']);
            }
        }

        return $fallback();
    }
}

Route::get('/avatar/{user}', function (User $user) {
    $fallbackFile = match ($user->role) {
        'admin' => 'admin.png',
        'expert' => 'expert.png',
        default => 'user.png',
    };

    return servePublicImage($user->avatar, function () use ($fallbackFile) {
        $fallbackPath = public_path('img/' . $fallbackFile);
        if (is_file($fallbackPath)) {
            return response()->file($fallbackPath, ['Cache-Control' => 'public, max-age=3600']);
        }

        abort(404);
    });
})->name('avatar.proxy');

Route::get('/topic-image/{topic}', function (Topic $topic) {
    $titleLower = mb_strtolower($topic->title);
    if (str_contains($titleLower, 'криптография')) {
        $fallbackFile = 'cripto.png';
    } elseif (str_contains($titleLower, 'web') || str_contains($titleLower, 'веб')) {
        $fallbackFile = 'web.png';
    } elseif (str_contains($titleLower, 'трафик')) {
        $fallbackFile = 'trafic.png';
    } elseif (str_contains($titleLower, 'аутентификация')) {
        $fallbackFile = 'auth.png';
    } else {
        $fallbackFile = 'logo.png';
    }

    return servePublicImage($topic->image, function () use ($fallbackFile) {
        $fallbackPath = public_path('img/' . $fallbackFile);
        if (is_file($fallbackPath)) {
            return response()->file($fallbackPath, ['Cache-Control' => 'public, max-age=3600']);
        }

        $logoPath = public_path('img/logo.png');
        if (is_file($logoPath)) {
            return response()->file($logoPath, ['Cache-Control' => 'public, max-age=3600']);
        }

        abort(404);
    });
})->name('topic.image.proxy');

Route::get('/level-image/{level}', function (Level $level) {
    $titleLower = mb_strtolower($level->title);
    if (str_contains($titleLower, 'базов') || str_contains($titleLower, 'easy')) {
        $fallbackFile = 'easy.png';
    } elseif (str_contains($titleLower, 'средн') || str_contains($titleLower, 'middle')) {
        $fallbackFile = 'midle.png';
    } elseif (str_contains($titleLower, 'продвинут') || str_contains($titleLower, 'hard')) {
        $fallbackFile = 'hard.png';
    } else {
        $fallbackFile = null;
    }

    return servePublicImage($level->image, function () use ($fallbackFile, $level) {
        if ($fallbackFile) {
            $fallbackPath = public_path('img/' . $fallbackFile);
            if (is_file($fallbackPath)) {
                return response()->file($fallbackPath, ['Cache-Control' => 'public, max-age=3600']);
            }
        }

        return redirect()->route('topic.image.proxy', ['topic' => $level->topic_id]);
    });
})->name('level.image.proxy');

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }

    $ratingService = app(UserRatingService::class);
    $experts = collect($ratingService->topUsers(3));

    if ($experts->isEmpty()) {
        $experts = collect([
            [
                'id' => 1,
                'name' => 'Клим',
                'avatar' => asset('img/user.png'),
                'progress' => 96,
                'accepted_count' => 12,
                'submitted_count' => 13,
                'completed_tasks' => [
                    ['task' => 'Создание ключей PGP', 'topic' => 'Криптография'],
                    ['task' => 'Экспорт и импорт ключа', 'topic' => 'Криптография'],
                    ['task' => 'Шифрование файлов', 'topic' => 'Криптография'],
                ],
                'awards' => ['🥇 Первый уровень', '🔥 Серия принятых задач', '👑 Топ‑специалист'],
            ],
        ]);
    }

    return view('landing', ['experts' => $experts]);
})->name('landing');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [TopicController::class, 'index'])->name('home');
    Route::get('/profile', [TopicController::class, 'profile'])->name('profile');
    Route::get('/topic/{id}', [TopicController::class, 'showTopic'])->name('topic.show');
    Route::get('/level/{id}', [TopicController::class, 'showLevel'])->name('level.show');

    Route::post('/tasks/{task}/submit', [TaskSubmissionController::class, 'submit'])->name('tasks.submit');

    Route::prefix('admin')->name('admin.')->middleware(['expert'])->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/submissions', [AdminSubmissionController::class, 'index'])->name('submissions.index');
        Route::get('/submissions/{submission}', [AdminSubmissionController::class, 'show'])->name('submissions.show');
        Route::post('/submissions/{submission}/accept', [AdminSubmissionController::class, 'accept'])->name('submissions.accept');
        Route::post('/submissions/{submission}/reject', [AdminSubmissionController::class, 'reject'])->name('submissions.reject');
        Route::post('/submissions/{submission}/feedback', [AdminSubmissionController::class, 'updateFeedback'])->name('submissions.feedback');

        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
        Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');

        Route::get('/topics', [AdminTopicController::class, 'index'])->name('topics.index');
        Route::get('/topics/{topic}/edit', [AdminTopicController::class, 'edit'])->name('topics.edit');
        Route::get('/levels/{level}/edit', [AdminLevelController::class, 'edit'])->name('levels.edit');
        Route::get('/levels/{level}/tasks/create', [AdminTaskController::class, 'create'])->name('tasks.create');
        Route::post('/levels/{level}/tasks', [AdminTaskController::class, 'store'])->name('tasks.store');

        Route::middleware(['admin'])->group(function () {
            Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

            Route::get('/achievements', [AdminAchievementController::class, 'index'])->name('achievements.index');
            Route::get('/achievements/create', [AdminAchievementController::class, 'create'])->name('achievements.create');
            Route::post('/achievements', [AdminAchievementController::class, 'store'])->name('achievements.store');
            Route::get('/achievements/{achievement}/edit', [AdminAchievementController::class, 'edit'])->name('achievements.edit');
            Route::put('/achievements/{achievement}', [AdminAchievementController::class, 'update'])->name('achievements.update');
            Route::delete('/achievements/{achievement}', [AdminAchievementController::class, 'destroy'])->name('achievements.destroy');

            Route::post('/topics/{topic}/achievements', [AdminAchievementController::class, 'storeForTopic'])->name('topics.achievements.store');

            Route::get('/topics/create', [AdminTopicController::class, 'create'])->name('topics.create');
            Route::post('/topics', [AdminTopicController::class, 'store'])->name('topics.store');
            Route::put('/topics/{topic}', [AdminTopicController::class, 'update'])->name('topics.update');
            Route::delete('/topics/{topic}', [AdminTopicController::class, 'destroy'])->name('topics.destroy');

            Route::get('/topics/{topic}/levels/create', [AdminLevelController::class, 'create'])->name('levels.create');
            Route::post('/topics/{topic}/levels', [AdminLevelController::class, 'store'])->name('levels.store');
            Route::put('/levels/{level}', [AdminLevelController::class, 'update'])->name('levels.update');
            Route::delete('/levels/{level}', [AdminLevelController::class, 'destroy'])->name('levels.destroy');

            Route::get('/tasks/{task}/edit', [AdminTaskController::class, 'edit'])->name('tasks.edit');
            Route::put('/tasks/{task}', [AdminTaskController::class, 'update'])->name('tasks.update');
            Route::delete('/tasks/{task}', [AdminTaskController::class, 'destroy'])->name('tasks.destroy');
        });
    });
});
