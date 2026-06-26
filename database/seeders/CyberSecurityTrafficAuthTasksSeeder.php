<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\LevelTheme;
use App\Models\Task;
use Illuminate\Database\Seeder;

class CyberSecurityTrafficAuthTasksSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedNetworkTrafficTasks();
        $this->seedAuthenticationTasks();

        $this->command?->info('Задания по темам «Сетевой трафик» и «Аутентификация» добавлены.');
    }

    private function seedNetworkTrafficTasks(): void
    {
        $this->createTasks('Сетевой трафик', 'Базовый уровень', 'Основы анализа сетевого трафика', [
            [
                'title' => 'Задание 1. Определение протокола по пакету',
                'content' => '<p>Аналитик получил фрагмент сетевого дампа:</p>
<table class="table table-sm table-bordered">
<thead><tr><th>№</th><th>Источник</th><th>Назначение</th><th>Порт назначения</th><th>Описание</th></tr></thead>
<tbody>
<tr><td>1</td><td>192.168.1.10</td><td>8.8.8.8</td><td>53</td><td>Запрос имени домена</td></tr>
<tr><td>2</td><td>192.168.1.10</td><td>93.184.216.34</td><td>80</td><td>Запрос веб-страницы</td></tr>
<tr><td>3</td><td>192.168.1.10</td><td>192.168.1.1</td><td>-</td><td>Проверка доступности узла</td></tr>
</tbody>
</table>
<p>Определите протоколы пакетов 1, 2 и 3. Ответ запишите через дефис в нижнем регистре.</p>
<p>Формат ответа: <code>flag{протокол1-протокол2-протокол3}</code>.</p>',
                'correct_answer' => 'flag{dns-http-icmp}',
                'order' => 1,
            ],
            [
                'title' => 'Задание 2. Определение клиента и сервера',
                'content' => '<p>Дан фрагмент сетевого соединения:</p>
<table class="table table-sm table-bordered">
<thead><tr><th>Источник</th><th>Порт источника</th><th>Назначение</th><th>Порт назначения</th><th>Флаги TCP</th></tr></thead>
<tbody>
<tr><td>10.0.0.15</td><td>51544</td><td>10.0.0.5</td><td>443</td><td>SYN</td></tr>
<tr><td>10.0.0.5</td><td>443</td><td>10.0.0.15</td><td>51544</td><td>SYN, ACK</td></tr>
</tbody>
</table>
<p>Определите IP-адрес клиента, который начал соединение.</p>
<p>Формат ответа: <code>flag{ip}</code>.</p>',
                'correct_answer' => 'flag{10.0.0.15}',
                'order' => 2,
            ],
            [
                'title' => 'Задание 3. Определение сервиса по порту',
                'content' => '<p>В журнале сетевого экрана зафиксировано подключение:</p>
<p><code>192.168.10.25:49231 → 192.168.10.10:22 TCP ALLOW</code></p>
<p>Определите, к какому сервису обращался пользователь.</p>
<p>Ответ запишите названием протокола в нижнем регистре.</p>
<p>Формат ответа: <code>flag{service}</code>.</p>',
                'correct_answer' => 'flag{ssh}',
                'order' => 3,
            ],
        ]);

        $this->createTasks('Сетевой трафик', 'Средний уровень', 'Анализ соединений и запросов', [
            [
                'title' => 'Задание 1. Анализ DNS-запросов',
                'content' => '<p>Аналитик изучает DNS-трафик рабочей станции:</p>
<table class="table table-sm table-bordered">
<thead><tr><th>Время</th><th>Источник</th><th>DNS-запрос</th><th>Тип</th></tr></thead>
<tbody>
<tr><td>10:14:01</td><td>192.168.1.34</td><td>corp.local</td><td>A</td></tr>
<tr><td>10:14:05</td><td>192.168.1.34</td><td>updates.microsoft.com</td><td>A</td></tr>
<tr><td>10:14:22</td><td>192.168.1.34</td><td>x7f3k9-c2-panel.net</td><td>A</td></tr>
</tbody>
</table>
<p>Найдите наиболее подозрительный домен, похожий на обращение к управляющему серверу.</p>
<p>Формат ответа: <code>flag{domain}</code>.</p>',
                'correct_answer' => 'flag{x7f3k9-c2-panel.net}',
                'order' => 1,
            ],
            [
                'title' => 'Задание 2. TCP three-way handshake',
                'content' => '<p>Даны TCP-пакеты одного соединения:</p>
<table class="table table-sm table-bordered">
<thead><tr><th>№</th><th>Источник</th><th>Назначение</th><th>Флаги</th></tr></thead>
<tbody>
<tr><td>1</td><td>172.16.0.12</td><td>172.16.0.20</td><td>SYN</td></tr>
<tr><td>2</td><td>172.16.0.20</td><td>172.16.0.12</td><td>SYN, ACK</td></tr>
<tr><td>3</td><td>172.16.0.12</td><td>172.16.0.20</td><td>ACK</td></tr>
</tbody>
</table>
<p>Определите правильную последовательность установки TCP-соединения.</p>
<p>Ответ запишите флагами через дефис без пробелов.</p>
<p>Формат ответа: <code>flag{...}</code>.</p>',
                'correct_answer' => 'flag{syn-synack-ack}',
                'order' => 2,
            ],
            [
                'title' => 'Задание 3. Анализ HTTP-запроса',
                'content' => '<p>В дампе найден HTTP-запрос:</p>
<pre class="mb-3">POST /login HTTP/1.1
Host: training.local
Content-Type: application/x-www-form-urlencoded

username=admin&amp;password=qwerty123</pre>
<p>Определите HTTP-метод, URI и переданный пароль.</p>
<p>Ответ запишите в формате:</p>
<p><code>flag{метод-uri-пароль}</code></p>
<p>Метод укажите в нижнем регистре.</p>',
                'correct_answer' => 'flag{post-/login-qwerty123}',
                'order' => 3,
            ],
        ]);

        $this->createTasks('Сетевой трафик', 'Продвинутый уровень', 'Выявление сетевых атак', [
            [
                'title' => 'Задание 1. Обнаружение сканирования портов',
                'content' => '<p>В журнале сетевого экрана зафиксированы события:</p>
<table class="table table-sm table-bordered">
<thead><tr><th>Время</th><th>Источник</th><th>Назначение</th><th>Порт</th><th>Флаг</th></tr></thead>
<tbody>
<tr><td>12:00:01</td><td>10.10.10.50</td><td>10.10.10.5</td><td>21</td><td>SYN</td></tr>
<tr><td>12:00:02</td><td>10.10.10.50</td><td>10.10.10.5</td><td>22</td><td>SYN</td></tr>
<tr><td>12:00:03</td><td>10.10.10.50</td><td>10.10.10.5</td><td>23</td><td>SYN</td></tr>
<tr><td>12:00:04</td><td>10.10.10.50</td><td>10.10.10.5</td><td>80</td><td>SYN</td></tr>
<tr><td>12:00:05</td><td>10.10.10.50</td><td>10.10.10.5</td><td>443</td><td>SYN</td></tr>
</tbody>
</table>
<p>Определите тип подозрительной активности.</p>
<p>Формат ответа: <code>flag{attack_type}</code>.</p>',
                'correct_answer' => 'flag{port_scan}',
                'order' => 1,
            ],
            [
                'title' => 'Задание 2. Поиск утечки данных в HTTP',
                'content' => '<p>В сетевом дампе найден фрагмент незашифрованного HTTP-трафика:</p>
<pre class="mb-3">GET /profile?user=ivan&amp;token=ab45cd99 HTTP/1.1
Host: portal.local</pre>
<p>Определите значение токена, переданного в URL.</p>
<p>Формат ответа: <code>flag{token}</code>.</p>',
                'correct_answer' => 'flag{ab45cd99}',
                'order' => 2,
            ],
            [
                'title' => 'Задание 3. Определение C2-активности',
                'content' => '<p>На рабочей станции обнаружены регулярные исходящие подключения:</p>
<table class="table table-sm table-bordered">
<thead><tr><th>Время</th><th>Источник</th><th>Назначение</th><th>URI</th><th>Периодичность</th></tr></thead>
<tbody>
<tr><td>09:00:00</td><td>192.168.5.44</td><td>185.10.44.91</td><td>/gate/checkin</td><td>каждые 60 секунд</td></tr>
<tr><td>09:01:00</td><td>192.168.5.44</td><td>185.10.44.91</td><td>/gate/checkin</td><td>каждые 60 секунд</td></tr>
<tr><td>09:02:00</td><td>192.168.5.44</td><td>185.10.44.91</td><td>/gate/checkin</td><td>каждые 60 секунд</td></tr>
</tbody>
</table>
<p>Такая периодическая активность характерна для связи вредоносной программы с управляющим сервером. Определите IP-адрес C2-сервера.</p>
<p>Формат ответа: <code>flag{ip}</code>.</p>',
                'correct_answer' => 'flag{185.10.44.91}',
                'order' => 3,
            ],
        ]);
    }

    private function seedAuthenticationTasks(): void
    {
        $this->createTasks('Аутентификация', 'Базовый уровень', 'Основы аутентификации', [
            [
                'title' => 'Задание 1. Определение фактора аутентификации',
                'content' => '<p>Пользователь входит в систему с помощью пароля и одноразового кода из SMS.</p>
<p>Определите тип аутентификации: однофакторная или двухфакторная.</p>
<p>Ответ запишите одним словом в нижнем регистре.</p>
<p>Формат ответа: <code>flag{answer}</code>.</p>',
                'correct_answer' => 'flag{двухфакторная}',
                'order' => 1,
            ],
            [
                'title' => 'Задание 2. Выбор надёжного пароля',
                'content' => '<p>Выберите наиболее надёжный пароль из списка:</p>
<ol>
<li><code>12345678</code></li>
<li><code>qwerty2024</code></li>
<li><code>Ivan2003</code></li>
<li><code>R7!mQ2#zL9</code></li>
</ol>
<p>В ответе укажите выбранный пароль.</p>
<p>Формат ответа: <code>flag{password}</code>.</p>',
                'correct_answer' => 'flag{R7!mQ2#zL9}',
                'order' => 2,
            ],
            [
                'title' => 'Задание 3. Определение учётных данных',
                'content' => '<p>Даны данные пользователя:</p>
<table class="table table-sm table-bordered">
<thead><tr><th>Поле</th><th>Значение</th></tr></thead>
<tbody>
<tr><td>Имя</td><td>Иван Петров</td></tr>
<tr><td>Логин</td><td>ipetrov</td></tr>
<tr><td>Пароль</td><td>Winter2026!</td></tr>
<tr><td>Отдел</td><td>Бухгалтерия</td></tr>
</tbody>
</table>
<p>Какие два значения являются учётными данными для входа?</p>
<p>Ответ запишите через дефис: логин-пароль.</p>
<p>Формат ответа: <code>flag{login-password}</code>.</p>',
                'correct_answer' => 'flag{ipetrov-Winter2026!}',
                'order' => 3,
            ],
        ]);

        $this->createTasks('Аутентификация', 'Средний уровень', 'Анализ журналов входа', [
            [
                'title' => 'Задание 1. Выявление подозрительного входа',
                'content' => '<p>Изучите журнал входов пользователя <strong>petrova</strong>:</p>
<table class="table table-sm table-bordered">
<thead><tr><th>Время</th><th>IP-адрес</th><th>Страна</th><th>Статус</th></tr></thead>
<tbody>
<tr><td>08:55</td><td>91.122.10.5</td><td>Россия</td><td>SUCCESS</td></tr>
<tr><td>09:10</td><td>91.122.10.5</td><td>Россия</td><td>SUCCESS</td></tr>
<tr><td>09:14</td><td>45.155.88.19</td><td>Нидерланды</td><td>SUCCESS</td></tr>
</tbody>
</table>
<p>Определите IP-адрес подозрительного успешного входа.</p>
<p>Формат ответа: <code>flag{ip}</code>.</p>',
                'correct_answer' => 'flag{45.155.88.19}',
                'order' => 1,
            ],
            [
                'title' => 'Задание 2. Причина отказа во входе',
                'content' => '<p>В журнале аутентификации зафиксировано событие:</p>
<pre class="mb-3">2026-06-01 11:42:18 user=admin status=FAILED reason=INVALID_PASSWORD ip=192.168.1.77</pre>
<p>Определите причину отказа во входе.</p>
<p>Ответ укажите значением поля <code>reason</code>.</p>
<p>Формат ответа: <code>flag{reason}</code>.</p>',
                'correct_answer' => 'flag{INVALID_PASSWORD}',
                'order' => 2,
            ],
            [
                'title' => 'Задание 3. Проверка наличия MFA',
                'content' => '<p>Система входа работает по следующей схеме:</p>
<ol>
<li>пользователь вводит логин и пароль;</li>
<li>система отправляет push-уведомление в мобильное приложение;</li>
<li>пользователь подтверждает вход в приложении.</li>
</ol>
<p>Определите, используется ли многофакторная аутентификация.</p>
<p>Ответ: <code>yes</code> или <code>no</code>.</p>
<p>Формат ответа: <code>flag{answer}</code>.</p>',
                'correct_answer' => 'flag{yes}',
                'order' => 3,
            ],
        ]);

        $this->createTasks('Аутентификация', 'Продвинутый уровень', 'Атаки на аутентификацию', [
            [
                'title' => 'Задание 1. Обнаружение brute force',
                'content' => '<p>Проанализируйте журнал входа:</p>
<table class="table table-sm table-bordered">
<thead><tr><th>Время</th><th>Пользователь</th><th>IP</th><th>Статус</th></tr></thead>
<tbody>
<tr><td>13:00:01</td><td>admin</td><td>203.0.113.55</td><td>FAILED</td></tr>
<tr><td>13:00:04</td><td>admin</td><td>203.0.113.55</td><td>FAILED</td></tr>
<tr><td>13:00:07</td><td>admin</td><td>203.0.113.55</td><td>FAILED</td></tr>
<tr><td>13:00:10</td><td>admin</td><td>203.0.113.55</td><td>FAILED</td></tr>
<tr><td>13:00:13</td><td>admin</td><td>203.0.113.55</td><td>SUCCESS</td></tr>
</tbody>
</table>
<p>Определите тип атаки.</p>
<p>Формат ответа: <code>flag{attack_type}</code>.</p>',
                'correct_answer' => 'flag{brute_force}',
                'order' => 1,
            ],
            [
                'title' => 'Задание 2. Признак компрометации аккаунта',
                'content' => '<p>Журнал активности пользователя <strong>manager</strong>:</p>
<table class="table table-sm table-bordered">
<thead><tr><th>Время</th><th>Событие</th><th>IP</th></tr></thead>
<tbody>
<tr><td>10:00</td><td>LOGIN_SUCCESS</td><td>192.168.0.14</td></tr>
<tr><td>10:03</td><td>PASSWORD_CHANGED</td><td>192.168.0.14</td></tr>
<tr><td>10:07</td><td>MFA_DISABLED</td><td>198.51.100.23</td></tr>
<tr><td>10:09</td><td>NEW_SESSION_CREATED</td><td>198.51.100.23</td></tr>
</tbody>
</table>
<p>Какое событие является наиболее опасным признаком компрометации?</p>
<p>Ответ укажите точным названием события.</p>
<p>Формат ответа: <code>flag{event}</code>.</p>',
                'correct_answer' => 'flag{MFA_DISABLED}',
                'order' => 2,
            ],
            [
                'title' => 'Задание 3. Защита от перебора паролей',
                'content' => '<p>В системе обнаружена проблема: злоумышленник может выполнять неограниченное количество попыток входа для одного пользователя.</p>
<p>Выберите наиболее подходящую меру защиты:</p>
<ol>
<li><code>password_hint</code> — показывать подсказку к паролю;</li>
<li><code>login_rate_limit</code> — ограничить количество попыток входа;</li>
<li><code>disable_logs</code> — отключить журналы входа;</li>
<li><code>short_passwords</code> — разрешить короткие пароли.</li>
</ol>
<p>В ответе укажите код правильной меры защиты.</p>
<p>Формат ответа: <code>flag{code}</code>.</p>',
                'correct_answer' => 'flag{login_rate_limit}',
                'order' => 3,
            ],
        ]);
    }

    private function createTasks(string $topicTitle, string $levelTitle, string $themeTitle, array $tasks): void
    {
        $level = Level::query()
            ->where('title', $levelTitle)
            ->whereHas('topic', fn ($q) => $q->where('title', $topicTitle))
            ->first();

        if (! $level) {
            $this->command?->error("Уровень «{$levelTitle}» темы «{$topicTitle}» не найден.");

            return;
        }

        $theme = LevelTheme::updateOrCreate(
            ['level_id' => $level->id, 'title' => $themeTitle],
            ['sort_order' => 1]
        );

        foreach ($tasks as $taskData) {
            Task::updateOrCreate(
                [
                    'level_id' => $level->id,
                    'title' => $taskData['title'],
                ],
                array_merge($taskData, [
                    'level_theme_id' => $theme->id,
                ])
            );
        }
    }
}
