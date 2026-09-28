<?php
// Shared helpers for the warranty claim system: claim numbers, private
// storage location, filename sanitizing, and signed file-view tokens.

require_once __DIR__ . '/db.php';

function warranty_uuid(): string
{
    $data = random_bytes(16);
    $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
    $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
    $hex = bin2hex($data);
    return substr($hex, 0, 8) . '-' . substr($hex, 8, 4) . '-' . substr($hex, 12, 4)
        . '-' . substr($hex, 16, 4) . '-' . substr($hex, 20, 12);
}

// Atomically allocates the next claim number for the current year. Must be
// called inside an open transaction on $pdo (service_submit.php does this):
// the UPDATE below takes an InnoDB row lock that is held until that
// transaction commits, so two simultaneous submissions serialize on it and
// can never receive the same number.
function warranty_next_claim_number(PDO $pdo): string
{
    $year = (int)date('Y');

    $pdo->prepare(
        'INSERT INTO warranty_counters (year, counter) VALUES (:y, 0)
         ON DUPLICATE KEY UPDATE year = year'
    )->execute(['y' => $year]);

    $pdo->prepare(
        'UPDATE warranty_counters SET counter = counter + 1 WHERE year = :y'
    )->execute(['y' => $year]);

    $stmt = $pdo->prepare('SELECT counter FROM warranty_counters WHERE year = :y');
    $stmt->execute(['y' => $year]);
    $counter = (int)$stmt->fetchColumn();

    return sprintf('JOMO-WAR-%d-%04d', $year, $counter);
}

// Private, non-web-accessible directory for warranty attachments. Defaults
// to a folder one level above public_html (outside the web root on typical
// hosting), overridable via WARRANTY_STORAGE_DIR in .env.
function warranty_storage_dir(): string
{
    $env = warranty_env();
    $dir = $env['WARRANTY_STORAGE_DIR'] ?? (dirname(__DIR__) . '/warranty_storage');

    if (!is_dir($dir)) {
        mkdir($dir, 0750, true);
    }

    // Defense in depth in case this ever ends up inside a web-served folder.
    $htaccess = $dir . '/.htaccess';
    if (!file_exists($htaccess)) {
        file_put_contents($htaccess, "Require all denied\nDeny from all\n");
    }
    $indexFile = $dir . '/index.html';
    if (!file_exists($indexFile)) {
        file_put_contents($indexFile, '');
    }

    return $dir;
}

// Turns a customer name into a short, filesystem-safe slug (e.g. "Rahul
// Kumar" -> "Rahul-Kumar") so staff can identify a claim's photos folder at
// a glance without opening the database.
function warranty_name_slug(string $name): string
{
    $name = trim($name);
    $name = preg_replace('/\s+/', '-', $name);
    $name = preg_replace('/[^A-Za-z0-9-]/', '', (string)$name);
    $name = trim((string)$name, '-');
    $name = mb_substr($name, 0, 40);
    return $name !== '' ? $name : 'customer';
}

function warranty_sanitize_filename(string $name): string
{
    $name = basename($name);
    $name = preg_replace('/[^A-Za-z0-9._-]/', '_', $name);
    $name = trim((string)$name, '._-');
    return $name !== '' ? $name : 'file';
}

// Short-lived HMAC-signed token so admins can view a private attachment
// without the file ever having a permanent public URL.
function warranty_sign_token(string $fileId, int $ttlSeconds = 300): string
{
    $env = warranty_env();
    $key = $env['WARRANTY_SIGNING_KEY'] ?? '';
    $exp = time() + $ttlSeconds;
    $payload = rtrim(strtr(base64_encode((string)json_encode(['id' => $fileId, 'exp' => $exp])), '+/', '-_'), '=');
    $sig = hash_hmac('sha256', $payload, $key);
    return $payload . '.' . $sig;
}

// Returns the file id if the token is valid and unexpired, otherwise null.
function warranty_verify_token(string $token): ?string
{
    $env = warranty_env();
    $key = $env['WARRANTY_SIGNING_KEY'] ?? '';
    $parts = explode('.', $token, 2);
    if (count($parts) !== 2) {
        return null;
    }
    [$payload, $sig] = $parts;
    $expected = hash_hmac('sha256', $payload, $key);
    if (!hash_equals($expected, $sig)) {
        return null;
    }
    $json = base64_decode(strtr($payload, '-_', '+/'));
    $data = $json !== false ? json_decode($json, true) : null;
    if (!is_array($data) || !isset($data['id'], $data['exp'])) {
        return null;
    }
    if (time() > (int)$data['exp']) {
        return null;
    }
    return (string)$data['id'];
}

const WARRANTY_STATUSES = [
    'NEW', 'UNDER_REVIEW', 'VISIT_SCHEDULED', 'ASSIGNED',
    'IN_PROGRESS', 'RESOLVED', 'CLOSED', 'REJECTED',
];
