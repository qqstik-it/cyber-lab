<?php

require __DIR__ . '/includes/bootstrap.php';

$username = portal_require_login();
$role = portal_cookie('role');

ob_start();

if ($role !== 'admin') {
    ?>
    <div class="card">
        <h2>Панель администратора</h2>
        <div class="alert alert-warn">Доступ запрещён. Требуется роль <strong>admin</strong>.</div>
        <p class="muted">Ваша текущая роль: <span class="role-badge"><?= portal_escape($role) ?></span></p>
        <div class="nav-links">
            <a href="dashboard.php">← В кабинет</a>
        </div>
    </div>
    <?php
    portal_layout('Доступ запрещён', (string) ob_get_clean());
    exit;
}
?>
<div class="card">
    <h2>Панель администратора</h2>
    <div class="alert alert-success">Доступ открыт. Добро пожаловать, <?= portal_escape($username) ?>.</div>
    <p>Секретный ключ системы:</p>
    <div class="flag-box">flag{admin_cookie}</div>
    <div class="nav-links">
        <a href="dashboard.php">← В кабинет</a>
        <a href="logout.php">Выйти</a>
    </div>
</div>
<?php
portal_layout('Admin', (string) ob_get_clean());
