<?php
// Shared session/auth guard for the warranty admin panel. Every page in
// this folder except login.php and setup_admin.php requires this.

error_reporting(0);
ini_set('display_errors', 0);
set_exception_handler(function (Throwable $e): void {
    error_log('Warranty admin error: ' . $e->getMessage());
    http_response_code(500);
    echo 'Something went wrong. Please try again in a moment, or contact IT if this continues.';
    exit;
});

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['warranty_admin_id'])) {
    header('Location: login.php');
    exit;
}

function warranty_csrf_token(): string
{
    if (empty($_SESSION['warranty_csrf'])) {
        $_SESSION['warranty_csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['warranty_csrf'];
}

function warranty_csrf_check(): void
{
    $token = $_POST['csrf'] ?? '';
    if (!hash_equals($_SESSION['warranty_csrf'] ?? '', $token)) {
        http_response_code(403);
        exit('Invalid or expired request. Please go back and try again.');
    }
}

function warranty_e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
