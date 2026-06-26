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
        $error = 'Заполните все поля.';
    } elseif (! preg_match('/^[a-zA-Z0-9_.@+-]{3,40}$/', $username)) {
        $error = 'Логин: от 3 до 40 символов (латиница, цифры, можно e-mail вида test@mail.ru).';
    } elseif (strlen($password) < 4) {
        $error = 'Пароль не короче 4 символов.';
    } elseif (isset($users[$username])) {
        $error = 'Такой логин уже занят.';
    } else {
        $users[$username] = [
            'password' => $password,
            'role' => 'user',
        ];
        portal_save_users($users);
        portal_set_auth_cookies($username, 'user');
        header('Location: dashboard.php');
        exit;
    }
}

ob_start();
?>
<div class="card">
    <h2>Регистрация</h2>
    <?php if ($error !== ''): ?>
        <div class="alert alert-error"><?= portal_escape($error) ?></div>
    <?php endif; ?>
    <form method="post" action="register.php">
        <div class="field">
            <label for="username">Логин</label>
            <input id="username" name="username" type="text" autocomplete="username" required placeholder="student1 или test@mail.ru" value="<?= portal_escape(portal_post('username')) ?>">
            <p class="muted" style="margin-top:0.35rem;">Можно обычный логин или e-mail. Пароль — любой, не короче 4 символов.</p>
        </div>
        <div class="field">
            <label for="password">Пароль</label>
            <input id="password" name="password" type="password" autocomplete="new-password" required placeholder="не короче 4 символов">
        </div>
        <button class="btn" type="submit">Создать аккаунт</button>
    </form>
    <div class="nav-links">
        <a href="index.php">Уже есть аккаунт</a>
    </div>
</div>
<?php
portal_layout('Регистрация', (string) ob_get_clean());
