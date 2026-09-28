<?php
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json');

ob_start();

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/warranty_helpers.php';
require_once __DIR__ . '/smtp_mailer.php';
require_once __DIR__ . '/service_webhook.php';

function clean_value(string $value): string
{
    return trim(strip_tags($value));
}

function safe_html(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function fail(string $message, int $code = 200): void
{
    ob_clean();
    http_response_code($code);
    echo json_encode(['status' => 'error', 'message' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    fail('Forbidden.', 403);
}

$fullname = clean_value($_POST['fullname'] ?? '');
$phone    = clean_value($_POST['phone'] ?? '');
$issue    = clean_value($_POST['issue'] ?? '');

if ($fullname === '' || $phone === '' || $issue === '') {
    fail('Please fill in your name, phone number, and describe the issue.');
}

// ---------------------------------------------------------------------
// Validate uploads (type, count, total size) before touching the DB or
// moving anything to disk. Frontend enforces the same rules, but they are
// re-checked here since the frontend check can always be bypassed.
// ---------------------------------------------------------------------
$max_files = 6;
$max_total = 35 * 1024 * 1024; // 35MB total across all files
$allowed_ext = [
    'jpg', 'jpeg', 'jfif', 'png', 'webp', 'gif', 'heic', 'heif', 'bmp', 'tif', 'tiff', 'avif',
    'mp4', 'mov', 'webm', 'avi', 'm4v', '3gp',
];

$incoming = [];
if (!empty($_FILES['media']) && is_array($_FILES['media']['name'])) {
    $count = count($_FILES['media']['name']);
    if ($count > $max_files) {
        fail("Please select up to $max_files files only.");
    }

    $total_size = 0;
    for ($i = 0; $i < $count; $i++) {
        $err = $_FILES['media']['error'][$i] ?? UPLOAD_ERR_NO_FILE;
        if ($err === UPLOAD_ERR_NO_FILE) {
            continue;
        }
        if ($err === UPLOAD_ERR_INI_SIZE || $err === UPLOAD_ERR_FORM_SIZE) {
            fail('Your files are over the 35 MB limit. Please choose fewer or smaller files.');
        }
        if ($err !== UPLOAD_ERR_OK) {
            fail('One of your files could not be uploaded. Please try again.');
        }

        $tmp_path  = $_FILES['media']['tmp_name'][$i];
        $orig_name = $_FILES['media']['name'][$i];
        $size      = (int)($_FILES['media']['size'][$i] ?? 0);
        $ext       = strtolower(pathinfo($orig_name, PATHINFO_EXTENSION));

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $tmp_path);
        finfo_close($finfo);

        $mime_ok = (strpos((string)$mime, 'image/') === 0 && $mime !== 'image/svg+xml')
            || strpos((string)$mime, 'video/') === 0;

        if (!in_array($ext, $allowed_ext, true) || !$mime_ok) {
            fail("\"$orig_name\" is not a supported photo/video format.");
        }

        $total_size += $size;
        $incoming[] = [
            'tmp_path' => $tmp_path,
            'name'     => warranty_sanitize_filename($orig_name),
            'ext'      => $ext,
            'mime'     => $mime,
            'size'     => $size,
        ];
    }

    if ($total_size > $max_total) {
        fail('Your files are over the 35 MB limit. Please choose fewer or smaller files.');
    }
}

// ---------------------------------------------------------------------
// Create the claim (DB is the source of truth for the claim number, so two
// simultaneous submissions can never collide).
// ---------------------------------------------------------------------
try {
    $pdo = warranty_db();
} catch (Throwable $e) {
    error_log('Warranty DB connect failed: ' . $e->getMessage());
    fail('Sorry, something went wrong. Please try again in a moment.');
}

// Guard against accidental double-submits (double-click / retry): if the
// same phone number filed the same issue in the last 30 seconds, return
// that claim instead of creating a second one.
$dupe_stmt = $pdo->prepare(
    'SELECT claim_number FROM warranty_claims
     WHERE phone = :phone AND description = :issue AND created_at >= (NOW() - INTERVAL 30 SECOND)
     ORDER BY created_at DESC LIMIT 1'
);
$dupe_stmt->execute(['phone' => $phone, 'issue' => $issue]);
$dupe = $dupe_stmt->fetchColumn();
if ($dupe) {
    ob_clean();
    echo json_encode([
        'status'  => 'success',
        'message' => "Thank you! Your warranty claim has been received. Your claim number is $dupe. Please keep this number for future reference.",
        'claim_number' => $dupe,
    ]);
    exit;
}

$claim_id = warranty_uuid();

try {
    $pdo->beginTransaction();
    $claim_number = warranty_next_claim_number($pdo);
    $stmt = $pdo->prepare(
        'INSERT INTO warranty_claims (id, claim_number, customer_name, phone, description, status)
         VALUES (:id, :cn, :name, :phone, :issue, "NEW")'
    );
    $stmt->execute([
        'id'    => $claim_id,
        'cn'    => $claim_number,
        'name'  => $fullname,
        'phone' => $phone,
        'issue' => $issue,
    ]);
    $pdo->commit();
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Warranty claim insert failed: ' . $e->getMessage());
    fail('Sorry, something went wrong creating your claim. Please try again.');
}

// ---------------------------------------------------------------------
// Move files into private storage and record them. If anything here
// fails, delete whatever was moved and the claim itself, rather than
// leaving an incomplete claim or orphaned files on disk.
// ---------------------------------------------------------------------
$moved_paths = [];
$file_count  = 0;

if ($incoming) {
    try {
        $storage_dir = warranty_storage_dir();
        $name_slug   = warranty_name_slug($fullname);
        $folder_name = $claim_number . '_' . $name_slug;
        $claim_dir   = $storage_dir . '/' . $folder_name;
        if (!is_dir($claim_dir) && !mkdir($claim_dir, 0750, true) && !is_dir($claim_dir)) {
            throw new RuntimeException('Could not create claim storage folder.');
        }

        $file_rows = [];
        $file_index = 0;
        foreach ($incoming as $f) {
            $file_index++;
            $disk_name = $name_slug . '-' . $file_index . '.' . $f['ext'];
            $dest = $claim_dir . '/' . $disk_name;

            if (!move_uploaded_file($f['tmp_path'], $dest)) {
                throw new RuntimeException('Could not save "' . $f['name'] . '".');
            }
            $moved_paths[] = $dest;

            $file_rows[] = [
                'id'           => warranty_uuid(),
                'claim_id'     => $claim_id,
                'file_name'    => $f['name'],
                'storage_path' => $folder_name . '/' . $disk_name,
                'file_type'    => $f['mime'],
                'file_size'    => $f['size'],
            ];
        }

        $pdo->beginTransaction();
        $ins = $pdo->prepare(
            'INSERT INTO warranty_claim_files (id, claim_id, file_name, storage_path, file_type, file_size)
             VALUES (:id, :claim_id, :file_name, :storage_path, :file_type, :file_size)'
        );
        foreach ($file_rows as $row) {
            $ins->execute($row);
        }
        $pdo->commit();
        $file_count = count($file_rows);
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        foreach ($moved_paths as $path) {
            if (is_file($path)) {
                unlink($path);
            }
        }
        // The customer clearly intended to attach evidence; if that failed,
        // don't leave a half-complete claim behind — remove it and ask them
        // to retry rather than reporting false success.
        $pdo->prepare('DELETE FROM warranty_claims WHERE id = :id')->execute(['id' => $claim_id]);
        error_log('Warranty claim file handling failed: ' . $e->getMessage());
        fail('Sorry, we could not save your photos/videos. Please try submitting again.');
    }
}

// ---------------------------------------------------------------------
// Admin notification email (care@ and info@). Files are private, so the
// email links to the admin claim page rather than the files themselves.
// ---------------------------------------------------------------------
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'jomointeriors.in';
$admin_link = $scheme . '://' . $host . '/admin/warranty/claim.php?id=' . urlencode($claim_id);

$admin_subject = "New Warranty Claim - $claim_number - $fullname";
$admin_body = "
<html>
<body style='font-family: Arial, sans-serif; color: #222;'>
    <div style='max-width: 620px; padding: 20px; border: 1px solid #eee;'>
        <h2 style='color: #d7a64a; margin-top: 0;'>New Warranty Claim</h2>
        <p><strong>Claim Number:</strong> " . safe_html($claim_number) . "</p>
        <p><strong>Name:</strong> " . safe_html($fullname) . "</p>
        <p><strong>Contact Number:</strong> " . safe_html($phone) . "</p>
        <p><strong>Issue Description:</strong><br>" . nl2br(safe_html($issue)) . "</p>
        <p><strong>Attachments:</strong> $file_count file(s)</p>
        <p><a href='" . safe_html($admin_link) . "'>View this claim in the admin panel</a></p>
        <hr style='border: 0; border-top: 1px solid #eee;'>
        <p style='font-size: 12px; color: #777;'>Source: JOMO Claim Warranty Form</p>
    </div>
</body>
</html>";

$admin_recipients = ['care@jomointeriors.in', 'info@jomointeriors.in'];
$admin_sent = false;
foreach ($admin_recipients as $to) {
    if (smtp_send($to, $admin_subject, $admin_body)) {
        $admin_sent = true;
    }
}
if (!$admin_sent) {
    file_put_contents(
        __DIR__ . '/mail_error.log',
        date('Y-m-d H:i:s') . " | Warranty form SMTP failed | $claim_number | $fullname | $phone\n",
        FILE_APPEND
    );
}

// ---------------------------------------------------------------------
// Log to Google Sheet via Apps Script webhook (best-effort; does not
// affect whether the claim itself is considered successful).
// ---------------------------------------------------------------------
send_to_service_sheet([
    'timestamp'  => date('Y-m-d H:i:s'),
    'fullname'   => $fullname,
    'phone'      => $phone,
    'issue'      => $issue,
    'file_links' => '',
    'file_count' => $file_count,
]);

// ---------------------------------------------------------------------
// Response
// ---------------------------------------------------------------------
ob_clean();
echo json_encode([
    'status'       => 'success',
    'message'      => "Thank you! Your warranty claim has been received. Your claim number is $claim_number. Please keep this number for future reference.",
    'claim_number' => $claim_number,
]);
