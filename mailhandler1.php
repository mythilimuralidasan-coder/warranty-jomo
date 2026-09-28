<?php
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json');

ob_start();

require_once __DIR__ . '/smtp_mailer.php';
require_once __DIR__ . '/webhook.php';

function clean_value($value)
{
    return trim(strip_tags((string) $value));
}

function safe_html($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ob_clean();
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Forbidden.']);
    exit;
}

$fullname    = clean_value($_POST['fullname'] ?? $_POST['name'] ?? '');
$phone       = clean_value($_POST['phone'] ?? '');
$email       = clean_value($_POST['email'] ?? '');
$requirement = clean_value($_POST['requirement'] ?? 'Free quotation request');
$message     = clean_value($_POST['message'] ?? 'Homepage free quotation form submission.');

if ($fullname === '' || $phone === '') {
    ob_clean();
    http_response_code(200);
    echo json_encode(['status' => 'error', 'message' => 'Please enter your name and contact number.']);
    exit;
}

// validate email if provided
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    ob_clean();
    http_response_code(200);
    echo json_encode(['status' => 'error', 'message' => 'Please enter a valid email address.']);
    exit;
}

$admin_subject = 'New Homepage Lead - ' . $fullname;

$admin_body = "
<html>
<body style='font-family: Arial, sans-serif; color: #222;'>
    <div style='max-width: 620px; padding: 20px; border: 1px solid #eee;'>
        <h2 style='color: #d7a64a; margin-top: 0;'>New Homepage Quotation Request</h2>
        <p><strong>Name:</strong> " . safe_html($fullname) . "</p>
        <p><strong>Contact Number:</strong> " . safe_html($phone) . "</p>
        <p><strong>Email:</strong> " . safe_html($email ?: 'Not provided') . "</p>
        <p><strong>Requirement:</strong> " . safe_html($requirement) . "</p>
        <p><strong>Message:</strong><br>" . nl2br(safe_html($message)) . "</p>
        <hr style='border: 0; border-top: 1px solid #eee;'>
        <p style='font-size: 12px; color: #777;'>Source: JOMO Homepage Banner Form</p>
    </div>
</body>
</html>";

$sent = smtp_send('enquiry@jomointeriors.in', $admin_subject, $admin_body);

$webhook_data = [
    "FULL_NAME" => $fullname,
    "PHONE_NUMBER" => $phone,
    "EMAIL" => $email,
    "SALUTATION" => "",
    "PROJECT_TYPE" => "",
    "SITE_STATUS" => "",
    "BUDGET" => "",
    "ADDRESS_LINE_1" => "",
    "ADDRESS_LINE_2" => "",
    "CITY" => "",
    "STATE" => "",
    "COUNTRY" => "",
    "PINCODE" => ""
];
send_to_webhook($webhook_data);

// Send confirmation to user if email provided
$user_sent = true;
if ($email !== '') {
    $user_subject = 'Thanks for contacting JOMO Interiors';
    $user_body = "<html><body style='font-family: Arial, sans-serif; color: #222;'>" .
        "<div style='max-width:620px;padding:20px;border:1px solid #eee;'>" .
        "<h2 style='color:#d7a64a;margin-top:0;'>Thank you for contacting JOMO</h2>" .
        "<p>Hi " . safe_html($fullname) . ",</p>" .
        "<p>Thank you for requesting a free quotation. We have received your request and our team will contact you shortly.</p>" .
        "<p><strong>Contact number:</strong> " . safe_html($phone) . "</p>" .
        "<p><strong>Requirement:</strong> " . safe_html($requirement) . "</p>" .
        "<hr style='border:0;border-top:1px solid #eee;'>" .
        "<p style='font-size:12px;color:#777;'>If you have additional details, reply to this email or call us at 9940187677.</p>" .
        "</div></body></html>";

    $user_sent = smtp_send($email, $user_subject, $user_body, 'enquiry@jomointeriors.in');
}

ob_clean();
if ($sent) {
    echo json_encode(['status' => 'success', 'message' => 'Success! Your request has been sent.']);
} else {
    file_put_contents(__DIR__ . '/mail_error.log',
        date('Y-m-d H:i:s') . " | Homepage form SMTP failed | $fullname | $phone\n", FILE_APPEND);
    // Even if SMTP failed we return success to avoid leaking info to user
    echo json_encode(['status' => 'success', 'message' => 'Request received! Our team will contact you shortly.']);
}
?>
