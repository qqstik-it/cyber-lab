<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\LevelTheme;
use App\Models\Task;
use Illuminate\Database\Seeder;

class SymbolicCryptoMiddleTasksSeeder extends Seeder
{
    private const HEX_TABLE = <<<'HTML'
<table class="table table-sm table-bordered mb-3">
    <thead><tr><th>Символ</th><th>Hex</th></tr></thead>
    <tbody>
        <tr><td>🛡️</td><td>0</td></tr>
        <tr><td>🔑</td><td>1</td></tr>
        <tr><td>💻</td><td>2</td></tr>
        <tr><td>🔒</td><td>3</td></tr>
        <tr><td>🌐</td><td>4</td></tr>
        <tr><td>⚙️</td><td>5</td></tr>
        <tr><td>🕵️</td><td>6</td></tr>
        <tr><td>📡</td><td>7</td></tr>
        <tr><td>🔥</td><td>8</td></tr>
        <tr><td>🗄️</td><td>9</td></tr>
        <tr><td>🦠</td><td>a</td></tr>
        <tr><td>🎣</td><td>b</td></tr>
        <tr><td>🧱</td><td>c</td></tr>
        <tr><td>📁</td><td>d</td></tr>
        <tr><td>🖥️</td><td>e</td></tr>
        <tr><td>🔐</td><td>f</td></tr>
    </tbody>
</table>
HTML;

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
        $cryptoMiddleLevel = Level::query()
            ->where('title', 'Средний уровень')
            ->whereHas('topic', fn ($q) => $q->where('title', 'Криптография'))
            ->first();

        if (! $cryptoMiddleLevel) {
            $this->command?->error('Средний уровень «Криптография» не найден. Сначала выполните DatabaseSeeder.');

            return;
        }

        $theme = LevelTheme::updateOrCreate(
            ['level_id' => $cryptoMiddleLevel->id, 'title' => 'Алфавит APT-CYBER'],
            ['sort_order' => 1]
        );

        $tasks = [
            [
                'title' => 'Задание 1. Символьная подстановка',
                'content' => '<p>Аналитик перехватил короткое сообщение группы <strong>APT-CYBER</strong>. Каждый символ соответствует одной латинской букве. Таблица соответствия известна:</p>'
                    . self::LETTER_TABLE
                    . '<p>Перехваченное сообщение:</p>'
                    . '<p class="fs-4 mb-3">🔑 🛡️ 💻 📡</p>'
                    . '<p>Расшифруйте текст и отправьте флаг в формате <code>flag{...}</code>.</p>',
                'correct_answer' => 'flag{bach}',
                'order' => 1,
            ],
            [
                'title' => 'Задание 2. Символы и Hex',
                'content' => '<p>Вредоносный модуль APT-CYBER кодирует данные в два шага: сначала текст переводится в <strong>шестнадцатеричную запись</strong> (hex), затем каждая hex-цифра заменяется символом из таблицы:</p>'
                    . self::HEX_TABLE
                    . '<p>Перехвачена цепочка символов:</p>'
                    . '<p class="fs-5 mb-3" style="line-height: 2;">🕵️ 🕵️ 🕵️ 🧱 🕵️ 🔑 🕵️ 📡 📡 🎣 🕵️ 🔒 📡 🗄️ 🕵️ 💻 🕵️ ⚙️ 📡 📡 📁 📡</p>'
                    . '<p>Восстановите исходный текст и найдите флаг.</p>',
                'correct_answer' => 'flag{cyber}',
                'order' => 2,
            ],
            [
                'title' => 'Задание 3. Символы и шифр Цезаря',
                'content' => '<p>Для защищённых каналов APT-CYBER использует двухэтапное <strong>шифрование</strong>:</p>'
                    . '<ol>'
                    . '<li>текст флага шифруется <strong>шифром Цезаря</strong> со сдвигом 3 вправо (как в базовом уровне);</li>'
                    . '<li>каждая буква заменяется символом из таблицы.</li>'
                    . '</ol>'
                    . self::LETTER_TABLE
                    . '<p>Перехвачена цепочка символов (только тело флага между <code>flag{</code> и <code>}</code>):</p>'
                    . '<p class="fs-4 mb-3">⚙️ 🔒 🌐</p>'
                    . '<p>Восстановите исходный флаг: сначала символы → буквы, затем сдвиг Цезаря <strong>на 3 влево</strong>.</p>'
                    . '<p>Ответ в формате <code>flag{...}</code>.</p>',
                'correct_answer' => 'flag{cab}',
                'order' => 3,
            ],
        ];

        foreach ($tasks as $taskData) {
            Task::updateOrCreate(
                [
                    'level_id' => $cryptoMiddleLevel->id,
                    'title' => $taskData['title'],
                ],
                array_merge($taskData, ['level_theme_id' => $theme->id])
            );
        }

        Task::query()
            ->where('level_id', $cryptoMiddleLevel->id)
            ->where('title', 'Задание 3. Цепочка Hex → Base64')
            ->delete();

        $this->command?->info('Подтема «Алфавит APT-CYBER» и 3 задания добавлены в средний уровень «Криптография».');
        $this->command?->info('Ответы: flag{bach}, flag{cyber}, flag{cab}');
    }
}
