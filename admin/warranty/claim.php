<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../../warranty_helpers.php';

$pdo = warranty_db();
$id = $_GET['id'] ?? '';

$stmt = $pdo->prepare('SELECT * FROM warranty_claims WHERE id = :id');
$stmt->execute(['id' => $id]);
$claim = $stmt->fetch();
if (!$claim) {
    http_response_code(404);
    die('Claim not found.');
}

$saved = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    warranty_csrf_check();

    $status = $_POST['status'] ?? '';
    $assigned_to = trim($_POST['assigned_to'] ?? '');
    $resolution_notes = trim($_POST['resolution_notes'] ?? '');

    if (!in_array($status, WARRANTY_STATUSES, true)) {
        http_response_code(400);
        die('Invalid status.');
    }

    $upd = $pdo->prepare(
        'UPDATE warranty_claims SET status = :status, assigned_to = :assigned_to, resolution_notes = :notes WHERE id = :id'
    );
    $upd->execute([
        'status'      => $status,
        'assigned_to' => $assigned_to !== '' ? $assigned_to : null,
        'notes'       => $resolution_notes !== '' ? $resolution_notes : null,
        'id'          => $id,
    ]);

    header('Location: claim.php?id=' . urlencode($id) . '&saved=1');
    exit;
}
$saved = isset($_GET['saved']);

// Re-fetch after any update (or on first load).
$stmt = $pdo->prepare('SELECT * FROM warranty_claims WHERE id = :id');
$stmt->execute(['id' => $id]);
$claim = $stmt->fetch();

$files_stmt = $pdo->prepare('SELECT * FROM warranty_claim_files WHERE claim_id = :id ORDER BY created_at ASC');
$files_stmt->execute(['id' => $id]);
$files = $files_stmt->fetchAll();

$csrf = warranty_csrf_token();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?= htmlspecialchars($claim['claim_number'], ENT_QUOTES, 'UTF-8') ?> — JOMO Warranty Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
  * { box-sizing: border-box; }
  body { font-family: Arial, sans-serif; background: #f4f5f7; color: #1c1d22; margin: 0; }
  header { background: #16161a; color: #fff; padding: 16px 24px; display: flex; justify-content: space-between; align-items: center; }
  header a { color: #d7a64a; text-decoration: none; font-size: 13px; }
  main { padding: 24px; max-width: 900px; margin: 0 auto; }
  .card { background: #fff; border-radius: 8px; padding: 20px 24px; margin-bottom: 18px; box-shadow: 0 1px 4px rgba(0,0,0,.06); }
  .card h3 { margin-top: 0; font-size: 14px; text-transform: uppercase; letter-spacing: .05em; color: #888; }
  .row { display: flex; gap: 24px; flex-wrap: wrap; }
  .row div { min-width: 180px; }
  .lbl { font-size: 12px; color: #888; }
  .val { font-size: 15px; font-weight: 600; margin-top: 2px; }
  .desc { white-space: pre-wrap; line-height: 1.5; }
  .thumbs { display: flex; gap: 12px; flex-wrap: wrap; }
  .thumb { width: 140px; }
  .thumb img, .thumb video { width: 140px; height: 140px; object-fit: cover; border-radius: 6px; background: #eee; display: block; }
  .thumb a { font-size: 11px; color: #555; text-decoration: none; display: block; margin-top: 4px; word-break: break-all; }
  select, input, textarea { width: 100%; padding: 9px 10px; border-radius: 6px; border: 1px solid #ccc; font-size: 14px; margin-top: 4px; font-family: inherit; }
  textarea { min-height: 80px; resize: vertical; }
  .field { margin-bottom: 14px; }
  button { padding: 10px 20px; background: #16161a; color: #fff; border: none; border-radius: 6px; font-weight: 700; cursor: pointer; }
  .ok { background: #dcfce7; color: #166534; padding: 10px 14px; border-radius: 6px; margin-bottom: 14px; font-size: 13px; }
  .back { font-size: 13px; color: #555; text-decoration: none; }
</style>
</head>
<body>
<header>
  <strong>JOMO Warranty Admin</strong>
  <span>
    <?= htmlspecialchars($_SESSION['warranty_admin_username'] ?? '', ENT_QUOTES, 'UTF-8') ?>
    &nbsp;·&nbsp; <a href="logout.php">Sign out</a>
  </span>
</header>
<main>
  <p><a class="back" href="index.php">&larr; All claims</a></p>
  <h2><?= htmlspecialchars($claim['claim_number'], ENT_QUOTES, 'UTF-8') ?></h2>

  <?php if ($saved): ?><div class="ok">Saved.</div><?php endif; ?>

  <div class="card">
    <h3>Customer Details</h3>
    <div class="row">
      <div><div class="lbl">Name</div><div class="val"><?= htmlspecialchars($claim['customer_name'], ENT_QUOTES, 'UTF-8') ?></div></div>
      <div><div class="lbl">Phone</div><div class="val"><?= htmlspecialchars($claim['phone'], ENT_QUOTES, 'UTF-8') ?></div></div>
      <div><div class="lbl">Email</div><div class="val"><?= htmlspecialchars($claim['email'] ?: '—', ENT_QUOTES, 'UTF-8') ?></div></div>
    </div>
  </div>

  <div class="card">
    <h3>Claim Details</h3>
    <div class="row" style="margin-bottom:14px;">
      <div><div class="lbl">Created</div><div class="val"><?= htmlspecialchars(date('M j, Y g:ia', strtotime($claim['created_at'])), ENT_QUOTES, 'UTF-8') ?></div></div>
      <div><div class="lbl">Last updated</div><div class="val"><?= htmlspecialchars(date('M j, Y g:ia', strtotime($claim['updated_at'])), ENT_QUOTES, 'UTF-8') ?></div></div>
    </div>
    <div class="lbl">Description</div>
    <div class="desc"><?= nl2br(htmlspecialchars($claim['description'], ENT_QUOTES, 'UTF-8')) ?></div>
  </div>

  <div class="card">
    <h3>Attachments</h3>
    <?php if (!$files): ?>
      <p style="color:#888;">No files attached.</p>
    <?php else: ?>
      <div class="thumbs">
        <?php foreach ($files as $f):
            $token = warranty_sign_token($f['id']);
            $url = 'file.php?token=' . urlencode($token);
            $is_video = strpos($f['file_type'], 'video/') === 0;
        ?>
          <div class="thumb">
            <?php if ($is_video): ?>
              <video src="<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>" controls preload="metadata"></video>
            <?php else: ?>
              <a href="<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">
                <img src="<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>" alt="">
              </a>
            <?php endif; ?>
            <a href="<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener"><?= htmlspecialchars($f['file_name'], ENT_QUOTES, 'UTF-8') ?></a>
          </div>
        <?php endforeach; ?>
      </div>
      <p style="font-size:11px;color:#999;margin-top:12px;">Links expire a few minutes after this page loads. Reload the page for fresh links.</p>
    <?php endif; ?>
  </div>

  <div class="card">
    <h3>Management</h3>
    <form method="post">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>">
      <div class="field">
        <label class="lbl">Status</label>
        <select name="status">
          <?php foreach (WARRANTY_STATUSES as $s): ?>
            <option value="<?= $s ?>" <?= $claim['status'] === $s ? 'selected' : '' ?>><?= str_replace('_', ' ', $s) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="field">
        <label class="lbl">Assigned to</label>
        <input type="text" name="assigned_to" value="<?= htmlspecialchars($claim['assigned_to'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Staff member name">
      </div>
      <div class="field">
        <label class="lbl">Internal resolution notes</label>
        <textarea name="resolution_notes" placeholder="Not visible to the customer"><?= htmlspecialchars($claim['resolution_notes'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
      </div>
      <button type="submit">Save changes</button>
    </form>
  </div>
</main>
</body>
</html>
