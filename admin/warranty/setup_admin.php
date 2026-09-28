<?php
// One-time page to create the first warranty admin account. Refuses to run
// once any admin already exists. Delete this file after using it.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../db.php';

try {
    $pdo = warranty_db();
    $count = (int)$pdo->query('SELECT COUNT(*) AS c FROM warranty_admins')->fetch()['c'];
} catch (Throwable $e) {
    http_response_code(500);
    die('Could not connect to the database. Check DB_HOST / DB_NAME / DB_USER / DB_PASS in .env and that warranty_schema.sql has been run.');
}

if ($count > 0) {
    http_response_code(403);
    die('Setup already completed — an admin account exists. Delete admin/warranty/setup_admin.php.');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = (string)($_POST['password'] ?? '');

    if (strlen($username) < 3) {
        $error = 'Username must be at least 3 characters.';
    } elseif (strlen($password) < 10) {
        $error = 'Password must be at least 10 characters.';
    } else {
        $stmt = $pdo->prepare(
            'INSERT INTO warranty_admins (id, username, password_hash) VALUES (:id, :u, :p)'
        );
        $stmt->execute([
            'id' => bin2hex(random_bytes(16)),
            'u'  => $username,
            'p'  => password_hash($password, PASSWORD_DEFAULT),
        ]);
        ?>
        <!doctype html><html><body style="font-family:Arial,sans-serif;padding:40px;">
        <h2>Admin account created.</h2>
        <p><a href="login.php">Go to login</a></p>
        <p style="color:#b91c1c;font-weight:bold;">Now delete admin/warranty/setup_admin.php from the server — leaving it live is a security risk.</p>
        </body></html>
        <?php
        exit;
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>JOMO Warranty Admin — First-time setup</title>
<style>
  body { font-family: Arial, sans-serif; background: #14151a; color: #eee; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
  form { background: #1d1e24; padding: 36px 32px; border-radius: 10px; width: 320px; box-shadow: 0 10px 30px rgba(0,0,0,.3); }
  h1 { font-size: 18px; margin: 0 0 8px; }
  p.hint { font-size: 12px; color: #9a9aa4; margin: 0 0 16px; }
  input { width: 100%; padding: 11px 12px; margin: 8px 0; border-radius: 6px; border: 1px solid #3a3b44; background: #26272e; color: #fff; box-sizing: border-box; font-size: 14px; }
  button { width: 100%; padding: 11px; margin-top: 8px; background: #d7a64a; border: none; border-radius: 6px; color: #16161a; font-weight: 700; cursor: pointer; font-size: 14px; }
  .err { color: #f87171; font-size: 13px; margin-bottom: 8px; }
</style>
</head>
<body>
<form method="post" autocomplete="off">
  <h1>Create the first admin account</h1>
  <p class="hint">This page only works once. Delete it after use.</p>
  <?php if ($error): ?><div class="err"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
  <input name="username" placeholder="Username" required autofocus>
  <input name="password" type="password" placeholder="Password (10+ characters)" required>
  <button type="submit">Create admin</button>
</form>
</body>
</html>
