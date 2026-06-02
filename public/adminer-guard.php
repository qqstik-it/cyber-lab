<?php

declare(strict_types=1);

/**
 * Minimal safety guard for local Adminer.
 * - Allows only local access (127.0.0.1 / ::1 / OSPanel loopback range 127.127.*.*)
 * - Then loads Adminer single-file app.
 */

$ip = $_SERVER['REMOTE_ADDR'] ?? '';
$isLocal =
    $ip === '127.0.0.1' ||
    $ip === '::1' ||
    str_starts_with($ip, '127.127.');

if (!$isLocal) {
    http_response_code(403);
    header('Content-Type: text/plain; charset=utf-8');
    echo "403 Forbidden\n";
    exit;
}

require __DIR__ . '/adminer.php';

