<?php

namespace App\Http\Controllers;

use App\Services\UserRatingService;

class LandingController extends Controller
{
    public function __invoke(UserRatingService $ratingService)
    {
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
    }
}
