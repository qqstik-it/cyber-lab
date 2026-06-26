<?php

require __DIR__ . '/includes/bootstrap.php';

portal_clear_auth_cookies();
header('Location: index.php');
exit;
