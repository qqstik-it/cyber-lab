<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\LevelTheme;
use App\Models\Task;
use Illuminate\Database\Seeder;

class SymbolicCryptoAdvancedTasksSeeder extends Seeder
{
    private const LETTER_TABLE = <<<'HTML'
<table class="table table-sm table-bordered mb-3">
    <thead><tr><th>Символ</th><th>Буква</th></tr></thead>
    <tbody>
        <tr><td>🛡️</td><td>A</td></tr>
        <tr><td>🔑</td><td>B</td></tr>
        <tr><td>💻</td><td>C</td></tr>
        <tr><td>🔒</td><td>D</td></tr>
        <tr><td>🌐</td><td>E</td></tr>
        <tr><td>⚙️</td><td>F</td></tr>
        <tr><td>🕵️</td><td>G</td></tr>
        <tr><td>📡</td><td>H</td></tr>
    </tbody>
</table>
HTML;

    public function run(): void
    {
        $cryptoAdvancedLevel = Level::query()
            ->where('title', 'Продвинутый уровень')
            ->whereHas('topic', fn ($q) => $q->where('title', 'Криптография'))
            ->first();

        if (! $cryptoAdvancedLevel) {
            $this->command?->error('Продвинутый уровень «Криптография» не найден. Сначала выполните DatabaseSeeder.');

            return;
        }

        $vigenereTheme = LevelTheme::updateOrCreate(
            ['level_id' => $cryptoAdvancedLevel->id, 'title' => 'Виженер и цепочки шифров'],
            ['sort_order' => 1]
        );

        $rsaTheme = LevelTheme::updateOrCreate(
            ['level_id' => $cryptoAdvancedLevel->id, 'title' => 'RSA'],
            ['sort_order' => 2]
        );

        $tasks = [
            [
                'theme_id' => $vigenereTheme->id,
                'title' => 'Задание 1. Шифр Виженера',
                'content' => '<p>APT-CYBER перешёл на <strong>полиалфавитный шифр Виженера</strong>. Ключ: <code>APT</code> (повторяется).</p>'
                    . '<p>Перехвачено сообщение:</p>'
                    . '<p class="fs-4 mb-3"><code>fatg&#123;kbgtgegx&#125;</code></p>'
                    . '<p>Расшифруйте флаг. Буквы вне фигурных скобок тоже зашифрованы. Сдвиг считается по латинскому алфавиту (a–z).</p>'
                    . '<p>Ответ в формате <code>flag{...}</code>.</p>',
                'correct_answer' => 'flag{vigenere}',
                'order' => 1,
            ],
            [
                'theme_id' => $vigenereTheme->id,
                'title' => 'Задание 2. Атбаш и Цезарь',
                'content' => '<p>Использована <strong>цепочка из двух шифров</strong> (в таком порядке):</p>'
                    . '<ol>'
                    . '<li>шифр <strong>Атбаш</strong>;</li>'
                    . '<li>шифр <strong>Цезаря</strong> со сдвигом 5 вправо.</li>'
                    . '</ol>'
                    . '<p>Перехвачено:</p>'
                    . '<p class="fs-4 mb-3"><code>ztey&#123;cxewr&#125;</code></p>'
                    . '<p>Расшифровывайте в обратном порядке: сначала Цезарь <strong>−5</strong>, затем Атбаш.</p>'
                    . '<p>Ответ в формате <code>flag{...}</code>.</p>',
                'correct_answer' => 'flag{chain}',
                'order' => 2,
            ],
            [
                'theme_id' => $vigenereTheme->id,
                'title' => 'Задание 3. Тройная цепочка APT-CYBER',
                'content' => '<p>Тройное шифрование тела флага:</p>'
                    . '<ol>'
                    . '<li><strong>Атбаш</strong>;</li>'
                    . '<li><strong>Цезарь</strong> со сдвигом 3 вправо;</li>'
                    . '<li>замена букв символами APT-CYBER (таблица ниже).</li>'
                    . '</ol>'
                    . self::LETTER_TABLE
                    . '<p>Перехвачены только символы тела флага:</p>'
                    . '<p class="fs-4 mb-3">🛡️ 💻 🔑</p>'
                    . '<p>Расшифровка: символы → буквы → Цезарь <strong>−3</strong> → Атбаш. Соберите флаг целиком.</p>'
                    . '<p>Ответ в формате <code>flag{...}</code>.</p>',
                'correct_answer' => 'flag{cab}',
                'order' => 3,
            ],
            [
                'theme_id' => $rsaTheme->id,
                'title' => 'Задание 4. Мини-RSA',
                'content' => '<p>Перехвачены параметры асимметричного шифрования:</p>'
                    . '<ul>'
                    . '<li><code>p = 61</code></li>'
                    . '<li><code>q = 53</code></li>'
                    . '<li><code>e = 17</code></li>'
                    . '<li><code>n = p × q = 3233</code></li>'
                    . '</ul>'
                    . '<p>Три буквы слова внутри флага зашифрованы по отдельности (числовой код ASCII, один символ — один блок):</p>'
                    . '<table class="table table-sm table-bordered mb-3">'
                    . '<thead><tr><th>Буква</th><th>Шифротекст c</th></tr></thead>'
                    . '<tbody>'
                    . '<tr><td>r</td><td>2412</td></tr>'
                    . '<tr><td>s</td><td>1230</td></tr>'
                    . '<tr><td>a</td><td>1632</td></tr>'
                    . '</tbody></table>'
                    . '<p>Найдите закрытую экспоненту <code>d</code>, расшифруйте каждый блок (<code>m = c<sup>d</sup> mod n</code>) и составьте флаг.</p>'
                    . '<p>Ответ в формате <code>flag{...}</code>.</p>',
                'correct_answer' => 'flag{rsa}',
                'order' => 4,
            ],
        ];

        foreach ($tasks as $taskData) {
            $themeId = $taskData['theme_id'];
            unset($taskData['theme_id']);

            Task::updateOrCreate(
                [
                    'level_id' => $cryptoAdvancedLevel->id,
                    'title' => $taskData['title'],
                ],
                array_merge($taskData, ['level_theme_id' => $themeId])
            );
        }

        $this->command?->info('Продвинутый уровень «Криптография»: 4 задания (Виженер, цепочки, RSA).');
        $this->command?->info('Ответы: flag{vigenere}, flag{chain}, flag{cab}, flag{rsa}');
    }
}
