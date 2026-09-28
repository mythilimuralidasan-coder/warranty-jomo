<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../../warranty_helpers.php';

$pdo = warranty_db();
$status_filter = $_GET['status'] ?? '';
$valid_status = in_array($status_filter, WARRANTY_STATUSES, true) ? $status_filter : '';

$sql = 'SELECT id, claim_number, customer_name, phone, description, status, created_at FROM warranty_claims';
$params = [];
if ($valid_status !== '') {
    $sql .= ' WHERE status = :s';
    $params['s'] = $valid_status;
}
$sql .= ' ORDER BY created_at DESC LIMIT 300';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$claims = $stmt->fetchAll();

$status_colors = [
    'NEW' => '#3b82f6', 'UNDER_REVIEW' => '#a855f7', 'VISIT_SCHEDULED' => '#f59e0b',
    'ASSIGNED' => '#f59e0b', 'IN_PROGRESS' => '#eab308', 'RESOLVED' => '#22c55e',
    'CLOSED' => '#6b7280', 'REJECTED' => '#ef4444',
];
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Warranty Claims — JOMO Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
  * { box-sizing: border-box; }
  body { font-family: Arial, sans-serif; background: #f4f5f7; color: #1c1d22; margin: 0; }
  header { background: #16161a; color: #fff; padding: 16px 24px; display: flex; justify-content: space-between; align-items: center; }
  header a { color: #d7a64a; text-decoration: none; font-size: 13px; }
  main { padding: 24px; max-width: 1100px; margin: 0 auto; }
  .filters { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 16px; }
  .filters a { padding: 6px 12px; border-radius: 16px; background: #fff; border: 1px solid #ddd; font-size: 12px; text-decoration: none; color: #333; }
  .filters a.on { background: #16161a; color: #fff; border-color: #16161a; }
  table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,.06); }
  th, td { padding: 12px 14px; text-align: left; font-size: 13px; border-bottom: 1px solid #eee; }
  th { background: #fafafa; font-weight: 700; color: #555; }
  tr:hover td { background: #fafbfc; }
  a.claim-link { color: #16161a; text-decoration: none; font-weight: 700; }
  .badge { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: 700; color: #fff; }
  .empty { padding: 40px; text-align: center; color: #888; }
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
  <h2>Warranty Claims</h2>
  <div class="filters">
    <a href="index.php" class="<?= $valid_status === '' ? 'on' : '' ?>">All</a>
    <?php foreach (WARRANTY_STATUSES as $s): ?>
      <a href="index.php?status=<?= urlencode($s) ?>" class="<?= $valid_status === $s ? 'on' : '' ?>"><?= str_replace('_', ' ', $s) ?></a>
    <?php endforeach; ?>
  </div>

  <?php if (!$claims): ?>
    <div class="empty">No warranty claims yet.</div>
  <?php else: ?>
  <table>
    <thead>
      <tr><th>Claim #</th><th>Customer</th><th>Phone</th><th>Issue</th><th>Status</th><th>Date</th></tr>
    </thead>
    <tbody>
      <?php foreach ($claims as $c): ?>
      <tr>
        <td><a class="claim-link" href="claim.php?id=<?= urlencode($c['id']) ?>"><?= htmlspecialchars($c['claim_number'], ENT_QUOTES, 'UTF-8') ?></a></td>
        <td><?= htmlspecialchars($c['customer_name'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($c['phone'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars(mb_strimwidth($c['description'], 0, 50, '…'), ENT_QUOTES, 'UTF-8') ?></td>
        <td><span class="badge" style="background: <?= $status_colors[$c['status']] ?? '#666' ?>"><?= str_replace('_', ' ', $c['status']) ?></span></td>
        <td><?= htmlspecialchars(date('M j, Y', strtotime($c['created_at'])), ENT_QUOTES, 'UTF-8') ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</main>
</body>
</html>
