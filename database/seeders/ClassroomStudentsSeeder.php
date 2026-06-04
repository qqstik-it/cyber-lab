<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClassroomStudentsSeeder extends Seeder
{
    /**
     * Студенты группы (роль user). Пароль по умолчанию: 12345678
     *
     * @var list<array{name: string, email: string}>
     */
    private const STUDENTS = [
        ['name' => 'Белый Александр Петрович', 'email' => 'belyy.ap@student.cyber-lab'],
        ['name' => 'Гулуев Бехтулла Мазанович', 'email' => 'guluyev.bm@student.cyber-lab'],
        ['name' => 'Есечкин Александр Дмитриевич', 'email' => 'esechkin.ad@student.cyber-lab'],
        ['name' => 'Кожевников Игорь Алексеевич', 'email' => 'kozhevnikov.ia@student.cyber-lab'],
        ['name' => 'Крамаренко Илья Игоревич', 'email' => 'kramarenko.ii@student.cyber-lab'],
        ['name' => 'Лузин Дмитрий Александрович', 'email' => 'luzin.da@student.cyber-lab'],
        ['name' => 'Мазутская Юлия Викторовна', 'email' => 'mazutskaya.yv@student.cyber-lab'],
        ['name' => 'Мустакимов Александр Владимирович', 'email' => 'mustakimov.av@student.cyber-lab'],
        ['name' => 'Серебренников Андрей Андреевич', 'email' => 'serebrennikov.aa@student.cyber-lab'],
        ['name' => 'Смирнов Александр Иванович', 'email' => 'smirnov.ai@student.cyber-lab'],
        ['name' => 'Шмаков Алексей Дмитриевич', 'email' => 'shmakov.ad@student.cyber-lab'],
        ['name' => 'Яман Дарья Владимировна', 'email' => 'yaman.dv@student.cyber-lab'],
    ];

    public function run(): void
    {
        $taskIds = $this->basicCryptoAndWebTaskIds();

        if ($taskIds->isEmpty()) {
            $this->command?->error('Не найдены задания базовых уровней «Криптография» и «WEB». Сначала выполните DatabaseSeeder.');

            return;
        }

        $now = now();

        foreach (self::STUDENTS as $student) {
            $user = User::updateOrCreate(
                ['email' => $student['email']],
                [
                    'name' => $student['name'],
                    'password' => Hash::make('12345678'),
                    'role' => 'user',
                ]
            );

            foreach ($taskIds as $taskId) {
                DB::table('task_submissions')->updateOrInsert(
                    [
                        'task_id' => $taskId,
                        'user_id' => $user->id,
                    ],
                    [
                        'answer' => 'Выполнено',
                        'status' => 'accepted',
                        'feedback' => 'Принято',
                        'reviewed_by' => null,
                        'reviewed_at' => $now,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );
            }
        }

        $this->ensureViktorStaysTopLeader();

        $this->command?->info('Создано/обновлено студентов: '.count(self::STUDENTS).'. Пароль: 12345678');
    }

    private function basicCryptoAndWebTaskIds()
    {
        return Task::query()
            ->whereHas('level', function ($q) {
                $q->where('title', 'Базовый уровень')
                    ->whereHas('topic', fn ($t) => $t->whereIn('title', ['Криптография', 'WEB']));
            })
            ->orderBy('id')
            ->pluck('id');
    }

    /**
     * Виктор остаётся #1 в рейтинге: те же базовые уровни + все остальные задания тем.
     */
    private function ensureViktorStaysTopLeader(): void
    {
        $viktor = User::query()->where('email', 'test@test.ru')->first();

        if (! $viktor) {
            return;
        }

        $allCryptoWebTaskIds = Task::query()
            ->whereHas('level.topic', fn ($q) => $q->whereIn('title', ['Криптография', 'WEB']))
            ->orderBy('id')
            ->pluck('id');

        $now = now();

        foreach ($allCryptoWebTaskIds as $taskId) {
            DB::table('task_submissions')->updateOrInsert(
                [
                    'task_id' => $taskId,
                    'user_id' => $viktor->id,
                ],
                [
                    'answer' => 'Выполнено',
                    'status' => 'accepted',
                    'feedback' => 'Отличная работа',
                    'reviewed_by' => null,
                    'reviewed_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
