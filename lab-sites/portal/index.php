<?php

require __DIR__ . '/includes/bootstrap.php';

if (portal_cookie('username') !== '') {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim(portal_post('username'));
    $password = portal_post('password');

    $users = portal_get_users();

    if ($username === '' || $password === '') {
        $error = 'Введите логин и пароль.';
    } elseif (! isset($users[$username]) || ! hash_equals((string) $users[$username]['password'], $password)) {
        $error = 'Неверный логин или пароль.';
    } else {
        portal_set_auth_cookies($username, 'user');
        header('Location: dashboard.php');
        exit;
    }
}

ob_start();
?>
<div class="card">
    <h2>Вход</h2>
    <?php if ($error !== ''): ?>
        <div class="alert alert-error"><?= portal_escape($error) ?></div>
    <?php endif; ?>
    <form method="post" action="index.php">
        <div class="field">
            <label for="username">Логин</label>
            <input id="username" name="username" type="text" autocomplete="username" required placeholder="тот же логин, что при регистрации" value="<?= portal_escape(portal_post('username')) ?>">
        </div>
        <div class="field">
            <label for="password">Пароль</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required>
        </div>
        <button class="btn" type="submit">Войти</button>
    </form>
    <div class="nav-links">
        <a href="register.php">Регистрация</a>
        <a href="admin.php">Панель admin</a>
    </div>
</div>
<?php
portal_layout('Вход', (string) ob_get_clean());
