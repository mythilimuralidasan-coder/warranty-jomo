<?php
// Streams one private warranty attachment. Requires BOTH an active admin
// session AND a valid, unexpired signed token — never a permanent public URL.

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../../warranty_helpers.php';

$token = $_GET['token'] ?? '';
$file_id = warranty_verify_token($token);
if (!$file_id) {
    http_response_code(403);
    exit('This link has expired. Go back to the claim page and reload it.');
}

$pdo = warranty_db();
$stmt = $pdo->prepare('SELECT storage_path, file_type, file_name FROM warranty_claim_files WHERE id = :id');
$stmt->execute(['id' => $file_id]);
$file = $stmt->fetch();
if (!$file) {
    http_response_code(404);
    exit('File not found.');
}

$storage_dir = warranty_storage_dir();
$path = realpath($storage_dir . '/' . $file['storage_path']);
$root = realpath($storage_dir);
if ($path === false || $root === false || strpos($path, $root) !== 0 || !is_file($path)) {
    http_response_code(404);
    exit('File not found.');
}

header('Content-Type: ' . $file['file_type']);
header('Content-Length: ' . filesize($path));
header('Content-Disposition: inline; filename="' . basename($file['file_name']) . '"');
header('Cache-Control: private, max-age=60, no-store');
header('X-Content-Type-Options: nosniff');
readfile($path);
