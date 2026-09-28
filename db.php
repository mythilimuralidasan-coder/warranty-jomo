<?php
// Shared MySQL connection for the warranty claim system.
// Reads credentials from .env (DB_HOST / DB_PORT / DB_NAME / DB_USER / DB_PASS).

function warranty_env(): array
{
    static $env = null;
    if ($env !== null) {
        return $env;
    }
    $path = __DIR__ . '/.env';
    $env = file_exists($path) ? (parse_ini_file($path) ?: []) : [];
    return $env;
}

function warranty_db(): PDO
{
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    $env  = warranty_env();
    $host = $env['DB_HOST'] ?? 'localhost';
    $port = $env['DB_PORT'] ?? '3306';
    $name = $env['DB_NAME'] ?? '';
    $user = $env['DB_USER'] ?? '';
    $pass = $env['DB_PASS'] ?? '';

    $dsn = "mysql:host=$host;port=$port;dbname=$name;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
    return $pdo;
}
