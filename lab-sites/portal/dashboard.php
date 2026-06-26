<?php

require __DIR__ . '/includes/bootstrap.php';

$username = portal_require_login();
$role = portal_cookie('role');

ob_start();
?>
<div class="card">
    <h2>Личный кабинет</h2>
    <p>Вы вошли как <strong><?= portal_escape($username) ?></strong></p>
    <p>Роль: <span class="role-badge"><?= portal_escape($role) ?></span></p>
    <p class="muted">Обычным пользователям панель администратора недоступна. Если вы считаете, что это ошибка — обратитесь в поддержку.</p>
    <div class="nav-links">
        <a href="admin.php">Панель admin</a>
        <a href="logout.php">Выйти</a>
    </div>
</div>
<?php
portal_layout('Кабинет', (string) ob_get_clean());
