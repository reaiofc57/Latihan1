<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

function current_user(): ?array {
    return $_SESSION['user'] ?? null;
}

function require_login(): void {
    if (!current_user()) {
        header('Location: ' . url('login.php?redirect=' . rawurlencode($_SERVER['REQUEST_URI'] ?? '')));
        exit;
    }
}

function login_user(array $row): void {
    unset($row['password']);
    $_SESSION['user'] = $row;
}

function logout_user(): void {
    unset($_SESSION['user']);
}
