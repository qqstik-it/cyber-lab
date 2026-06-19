<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Topic;
use App\Models\Level;
use App\Models\LevelTheme;
use App\Models\Task;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Создаем тестовых пользователей
        $katya = User::updateOrCreate(
            ['email' => 'ukustik.kata@gmail.com'],
            [
                'name' => 'Катя Кустова',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
            ]
        );
        $svetlana = User::updateOrCreate(
            ['email' => 'svetlana@test.ru'],
            [
                'name' => 'Светлана',
                'password' => Hash::make('12345678'),
                'role' => 'expert',
            ]
        );
        $viktor = User::updateOrCreate(
            ['email' => 'test@test.ru'],
            [
                'name' => 'Виктор',
                'password' => Hash::make('12345678'),
                'role' => 'user',
            ]
        );
        $klim = User::updateOrCreate(
            ['email' => 'klim@test.ru'],
            [
                'name' => 'Клим',
                'password' => Hash::make('12345678'),
                'role' => 'user',
            ]
        );
        $andrey = User::updateOrCreate(
            ['email' => 'andrey@test.ru'],
            [
                'name' => 'Андрей',
                'password' => Hash::make('12345678'),
                'role' => 'user',
            ]
        );

        // Очистка таблиц
        Schema::disableForeignKeyConstraints();
        DB::table('task_submissions')->truncate();
        DB::table('achievements')->truncate();
        DB::table('tasks')->truncate();
        DB::table('levels')->truncate();
        DB::table('topics')->truncate();
        DB::table('user_stats')->truncate();
        Schema::enableForeignKeyConstraints();

        // Добавляем темы
        $topics = [
            [
                'id' => 1,
                'title' => 'Криптография',
                'author' => 'Катя Кустова',
                'image' => 'img/cripto.png',
                'progress_current' => 1,
                'progress_total' => 3,
                'levels' => [
                    [
                        'id' => 1,
                        'title' => 'Базовый уровень',
                        'subtitle' => 'Криптосистема PGP',
                        'tasks' => [
                            [
                                'title' => 'Задание 1. Цезарь: сдвиг 3',
                                'content' => '<p>Перехвачено сообщение: iodj{fbrhu_lv_hdvb}. Известно, что использован шифр Цезаря со сдвигом на 3 вправо. Расшифруйте.</p>',
                                'correct_answer' => 'flag{cyber_is_easy}',
                                'order' => 1
                            ],
                            [
                                'title' => 'Задание 2. Цезарь: сдвиг 5',
                                'content' => '<p>Сообщение зашифровано шифром Цезаря со сдвигом на 5 вправо: kqfl{hjxfw_tqfxxnh}. Найдите флаг.</p>',
                                'correct_answer' => 'flag{ceasar_classic}',
                                'order' => 2
                            ],
                            [
                                'title' => 'Задание 3. Цезарь: сдвиг 7',
                                'content' => '<p>Дано сообщение: mshn{jybwav_ihzpj}. Известно, что сдвиг равен 7 вправо.</p>',
                                'correct_answer' => 'flag{crypto_basic}',
                                'order' => 3
                            ],
                            [
                                'title' => 'Задание 4. Атбаш: первый флаг',
                                'content' => '<p>Перехвачено сообщение: <code>uozt{xibkgl_yzhrx}</code>. Известно, что использован шифр Атбаш — каждая буква заменена на симметричную в латинском алфавите (a↔z, b↔y, c↔x и т.д.). Расшифруйте сообщение и найдите флаг.</p>',
                                'correct_answer' => 'flag{crypto_basic}',
                                'order' => 4
                            ],
                            [
                                'title' => 'Задание 5. Атбаш: тайное сообщение',
                                'content' => '<p>Сообщение зашифровано шифром Атбаш: <code>uozt{zgyzhs_hxsvnv}</code>. Восстановите исходный флаг.</p>',
                                'correct_answer' => 'flag{atbash_scheme}',
                                'order' => 5
                            ],
                            [
                                'title' => 'Задание 6. Атбаш: без лишнего',
                                'content' => '<p>Получена строка: <code>uozt{hvxfiv_nvhhztv}</code>. Каждая буква заменена на симметричную по алфавиту (шифр Атбаш). Расшифруйте сообщение.</p>',
                                'correct_answer' => 'flag{secure_message}',
                                'order' => 6
                            ],
                        ]
                    ],
                    ['id' => 2, 'title' => 'Средний уровень', 'subtitle' => 'Алгоритмы AES', 'tasks' => []],
                    ['id' => 3, 'title' => 'Продвинутый уровень', 'subtitle' => 'RSA и подписи', 'tasks' => []],
                ]
            ],
            [
                'id' => 2,
                'title' => 'WEB',
                'author' => 'Катя Кустова',
                'image' => 'img/web.png',
                'progress_current' => 1,
                'progress_total' => 3,
                'levels' => [
                    ['id' => 4, 'title' => 'Базовый уровень', 'subtitle' => 'Теги и атрибуты', 'tasks' => []],
                    ['id' => 5, 'title' => 'Средний уровень', 'subtitle' => 'CSS селекторы', 'tasks' => []],
                    ['id' => 6, 'title' => 'Продвинутый уровень', 'subtitle' => 'CSS свойства', 'tasks' => []],
                ]
            ],
            [
                'id' => 3,
                'title' => 'Сетевой трафик',
                'author' => 'Катя Кустова',
                'image' => 'img/trafic.png',
                'progress_current' => 0,
                'progress_total' => 3,
                'levels' => [
                    ['id' => 7, 'title' => 'Базовый уровень', 'subtitle' => 'Протоколы TCP/IP', 'tasks' => []],
                    ['id' => 8, 'title' => 'Средний уровень', 'subtitle' => 'Анализ пакетов', 'tasks' => []],
                    ['id' => 9, 'title' => 'Продвинутый уровень', 'subtitle' => 'Firewalls', 'tasks' => []],
                ]
            ],
            [
                'id' => 4,
                'title' => 'Аутентификация',
                'author' => 'Катя Кустова',
                'image' => 'img/auth.png',
                'progress_current' => 0,
                'progress_total' => 3,
                'levels' => [
                    ['id' => 10, 'title' => 'Базовый уровень', 'subtitle' => 'Типы аутентификации', 'tasks' => []],
                    ['id' => 11, 'title' => 'Средний уровень', 'subtitle' => 'Двухфакторная авторизация', 'tasks' => []],
                    ['id' => 12, 'title' => 'Продвинутый уровень', 'subtitle' => 'Биометрия', 'tasks' => []],
                ]
            ]
        ];

        foreach ($topics as $topicData) {
            $levels = $topicData['levels'];
            unset($topicData['levels']);
            
            $topic = Topic::create($topicData);
            
            foreach ($levels as $levelData) {
                $tasks = $levelData['tasks'] ?? [];
                unset($levelData['tasks']);
                
                $level = $topic->levels()->create($levelData);
                
                foreach ($tasks as $taskData) {
                    $level->tasks()->create($taskData);
                }
            }
        }

        $this->call(AchievementSeeder::class);

        // Подтемы для базового уровня криптографии
        $cryptoBaseLevel = Level::query()
            ->where('title', 'Базовый уровень')
            ->whereHas('topic', fn ($q) => $q->where('title', 'Криптография'))
            ->first();

        if ($cryptoBaseLevel) {
            $cryptoThemes = [
                1 => 'Шифр Цезаря',
                2 => 'Шифр Атбаш',
            ];

            foreach ($cryptoThemes as $sortOrder => $themeTitle) {
                LevelTheme::updateOrCreate(
                    ['level_id' => $cryptoBaseLevel->id, 'title' => $themeTitle],
                    ['sort_order' => $sortOrder]
                );
            }

            $themeByTitle = LevelTheme::query()
                ->where('level_id', $cryptoBaseLevel->id)
                ->pluck('id', 'title');

            Task::query()
                ->where('level_id', $cryptoBaseLevel->id)
                ->whereIn('title', [
                    'Задание 1. Цезарь: сдвиг 3',
                    'Задание 2. Цезарь: сдвиг 5',
                    'Задание 3. Цезарь: сдвиг 7',
                ])
                ->update(['level_theme_id' => $themeByTitle['Шифр Цезаря'] ?? null]);

            Task::query()
                ->where('level_id', $cryptoBaseLevel->id)
                ->whereIn('title', [
                    'Задание 4. Атбаш: первый флаг',
                    'Задание 5. Атбаш: тайное сообщение',
                    'Задание 6. Атбаш: без лишнего',
                ])
                ->update(['level_theme_id' => $themeByTitle['Шифр Атбаш'] ?? null]);
        }

        // Демонстрационные прохождения для лендинга (топ-3)
        $cryptoTasks = Task::query()
            ->whereHas('level.topic', fn ($q) => $q->where('title', 'Криптография'))
            ->orderBy('id')
            ->get();

        $webTasks = Task::query()
            ->whereHas('level.topic', fn ($q) => $q->where('title', 'WEB'))
            ->orderBy('id')
            ->get();

        // Виктор полностью прошел Криптографию и WEB
        foreach ($cryptoTasks->concat($webTasks) as $task) {
            DB::table('task_submissions')->insert([
                'task_id' => $task->id,
                'user_id' => $viktor->id,
                'answer' => 'Выполнено',
                'status' => 'accepted',
                'feedback' => 'Отличная работа',
                'reviewed_by' => null,
                'reviewed_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Клим: сильный прогресс, но ниже Виктора
        foreach ($cryptoTasks->take(3) as $task) {
            DB::table('task_submissions')->insert([
                'task_id' => $task->id,
                'user_id' => $klim->id,
                'answer' => 'Готово',
                'status' => 'accepted',
                'feedback' => 'Принято',
                'reviewed_by' => null,
                'reviewed_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        if ($webTasks->isNotEmpty()) {
            DB::table('task_submissions')->insert([
                'task_id' => $webTasks->first()->id,
                'user_id' => $klim->id,
                'answer' => 'Готово',
                'status' => 'accepted',
                'feedback' => 'Принято',
                'reviewed_by' => null,
                'reviewed_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Андрей: базовый прогресс
        foreach ($cryptoTasks->take(2) as $task) {
            DB::table('task_submissions')->insert([
                'task_id' => $task->id,
                'user_id' => $andrey->id,
                'answer' => 'Готово',
                'status' => 'accepted',
                'feedback' => 'Принято',
                'reviewed_by' => null,
                'reviewed_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Добавляем статс
        DB::table('user_stats')->insert([
            ['title' => 'Криптография', 'progress' => 80, 'color' => 'warning'],
            ['title' => 'Защита паролей', 'progress' => 100, 'color' => 'primary'],
            ['title' => 'Анализ сетевого трафика', 'progress' => 45, 'color' => 'success'],
            ['title' => 'Аутентификация', 'progress' => 20, 'color' => 'danger'],
        ]);
    }

}
