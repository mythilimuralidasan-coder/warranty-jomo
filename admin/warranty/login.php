<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../db.php';

if (!empty($_SESSION['warranty_admin_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = (string)($_POST['password'] ?? '');

    try {
        $pdo = warranty_db();
        $stmt = $pdo->prepare('SELECT id, username, password_hash FROM warranty_admins WHERE username = :u');
        $stmt->execute(['u' => $username]);
        $admin = $stmt->fetch();
    } catch (Throwable $e) {
        error_log('Warranty admin login DB error: ' . $e->getMessage());
        $admin = null;
    }

    if ($admin && password_verify($password, $admin['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['warranty_admin_id'] = $admin['id'];
        $_SESSION['warranty_admin_username'] = $admin['username'];
        header('Location: index.php');
        exit;
    }

    usleep(400000); // slow down brute-force guessing a little
    $error = 'Invalid username or password.';
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>JOMO Warranty Admin — Sign in</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
  body { font-family: Arial, sans-serif; background: #14151a; color: #eee; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
  form { background: #1d1e24; padding: 36px 32px; border-radius: 10px; width: 300px; box-shadow: 0 10px 30px rgba(0,0,0,.3); }
  h1 { font-size: 18px; margin: 0 0 20px; }
  input { width: 100%; padding: 11px 12px; margin: 8px 0; border-radius: 6px; border: 1px solid #3a3b44; background: #26272e; color: #fff; box-sizing: border-box; font-size: 14px; }
  button { width: 100%; padding: 11px; margin-top: 8px; background: #d7a64a; border: none; border-radius: 6px; color: #16161a; font-weight: 700; cursor: pointer; font-size: 14px; }
  .err { color: #f87171; font-size: 13px; margin-bottom: 8px; }
</style>
</head>
<body>
<form method="post" autocomplete="off">
  <h1>JOMO Warranty Admin</h1>
  <?php if ($error): ?><div class="err"><?= warranty_e_local($error) ?></div><?php endif; ?>
  <input name="username" placeholder="Username" required autofocus autocomplete="username">
  <input name="password" type="password" placeholder="Password" required autocomplete="current-password">
  <button type="submit">Sign in</button>
</form>
</body>
</html>
<?php
function warranty_e_local(string $v): string { return htmlspecialchars($v, ENT_QUOTES, 'UTF-8'); }
