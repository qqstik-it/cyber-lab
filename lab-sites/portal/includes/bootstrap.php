<?php

$dataDir = dirname(__DIR__) . '/data';
$usersFile = $dataDir . '/users.json';

function portal_users_file()
{
    global $usersFile;

    return $usersFile;
}

function portal_get_users()
{
    $file = portal_users_file();
    if (! is_file($file)) {
        return [];
    }

    $data = json_decode((string) file_get_contents($file), true);

    return is_array($data) ? $data : [];
}

function portal_save_users(array $users)
{
    global $dataDir;

    if (! is_dir($dataDir)) {
        mkdir($dataDir, 0755, true);
    }

    file_put_contents(
        portal_users_file(),
        json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );
}

function portal_set_auth_cookies($username, $role = 'user')
{
    $expire = time() + 86400;
    $path = '/';

    setcookie('username', $username, $expire, $path);
    setcookie('role', $role, $expire, $path);

    $_COOKIE['username'] = $username;
    $_COOKIE['role'] = $role;
}

function portal_clear_auth_cookies()
{
    $past = time() - 3600;
    $path = '/';

    setcookie('username', '', $past, $path);
    setcookie('role', '', $past, $path);

    unset($_COOKIE['username'], $_COOKIE['role']);
}

function portal_require_login()
{
    $username = isset($_COOKIE['username']) ? (string) $_COOKIE['username'] : '';
    if ($username === '') {
        header('Location: index.php');
        exit;
    }

    return $username;
}

function portal_escape($value)
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function portal_cookie($name)
{
    return isset($_COOKIE[$name]) ? (string) $_COOKIE[$name] : '';
}

function portal_post($name)
{
    return isset($_POST[$name]) ? (string) $_POST[$name] : '';
}

function portal_layout($title, $body)
{
    $titleEscaped = portal_escape($title);
    echo <<<HTML
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$titleEscaped} · Кибер-Портал</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="wrap">
        <div class="brand">
            <div class="brand-mark">P</div>
            <div>
                <h1>Кибер-Портал</h1>
                <p>Учебный сервис авторизации</p>
            </div>
        </div>
        {$body}
    </div>
</body>
</html>
HTML;
}
