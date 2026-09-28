<?php
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json');

ob_start();

require_once __DIR__ . '/smtp_mailer.php';
require_once __DIR__ . '/webhook.php';

function clean_value(string $value): string
{
    return trim(strip_tags($value));
}

function safe_html(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ob_clean();
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Forbidden.']);
    exit;
}

$fullname = clean_value($_POST['fullname'] ?? trim((($_POST['firstName'] ?? '') . ' ' . ($_POST['lastName'] ?? ''))));
$phone = clean_value($_POST['phone'] ?? '');
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$property = clean_value($_POST['property'] ?? 'Not specified');
$budget = clean_value($_POST['budget'] ?? 'Not specified');
$pincode = clean_value($_POST['pincode'] ?? 'Not provided');

if ($fullname === '' || $phone === '' || $property === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    ob_clean();
    http_response_code(200);
    echo json_encode(['status' => 'error', 'message' => 'Please fill in your name, contact number, email address, and property type.']);
    exit;
}

// --- Admin notification ---
$admin_subject = 'New Contact Page Lead - ' . $fullname;

$admin_body = "
<html>
<body style='font-family: Arial, sans-serif; color: #222;'>
    <div style='max-width: 620px; padding: 20px; border: 1px solid #eee;'>
        <h2 style='color: #d7a64a; margin-top: 0;'>New Consultation Enquiry</h2>
        <p><strong>Name:</strong> " . safe_html($fullname) . "</p>
        <p><strong>Contact Number:</strong> " . safe_html($phone) . "</p>
        <p><strong>Email:</strong> " . safe_html($email) . "</p>
        <p><strong>Property Type:</strong> " . safe_html($property) . "</p>
        <p><strong>Budget:</strong> " . safe_html($budget) . "</p>
        <p><strong>Pincode:</strong> " . safe_html($pincode) . "</p>
        <hr style='border: 0; border-top: 1px solid #eee;'>
        <p style='font-size: 12px; color: #777;'>Source: JOMO Contact Page Form</p>
    </div>
</body>
</html>";

$admin_sent = smtp_send('info@jomointeriors.in', $admin_subject, $admin_body, $email);

$mapped_project_type = $property;

$budget_map = [
    'Under 5 Lakhs' => '500000',
    '5 - 10 Lakhs' => '1000000',
    '10 - 15 Lakhs' => '1500000',
    '15 - 25 Lakhs' => '2500000',
    '25 Lakhs and above' => '3000000',
    'Not specified' => '0'
];
$mapped_budget = $budget_map[$budget] ?? '0';

$webhook_data = [
    "FULL_NAME" => $fullname,
    "PHONE_NUMBER" => $phone,
    "EMAIL" => $email,
    "SALUTATION" => "",
    "PROJECT_TYPE" => $mapped_project_type,
    "SITE_STATUS" => "",
    "BUDGET" => $mapped_budget,
    "ADDRESS_LINE_1" => "",
    "ADDRESS_LINE_2" => "",
    "CITY" => "",
    "STATE" => "",
    "COUNTRY" => "",
    "PINCODE" => $pincode ? $pincode : "600001"
];
file_put_contents(__DIR__ . '/webhook_debug.log', date('Y-m-d H:i:s') . " | Sending Payload: " . json_encode($webhook_data) . "\n", FILE_APPEND);
send_to_webhook($webhook_data);

// --- User confirmation ---
$user_sent = true;
$user_subject = 'Thank you for your response from JOMO Home Interiors';

$user_body = "
<html>
<body style='font-family: Arial, sans-serif; color: #222;'>
    <div style='max-width: 620px; padding: 24px; border: 1px solid #eee;'>
        <h2 style='color: #d7a64a; margin-top: 0;'>Thank You</h2>
        <p>Dear " . safe_html($fullname) . ",</p>
        <p>Thank you for your response. Our team will contact you soon.</p>
        <p><strong>Your details:</strong></p>
        <ul>
            <li><strong>Name:</strong> " . safe_html($fullname) . "</li>
            <li><strong>Phone Number:</strong> " . safe_html($phone) . "</li>
            <li><strong>Email:</strong> " . safe_html($email) . "</li>
            <li><strong>Property Type:</strong> " . safe_html($property) . "</li>
            <li><strong>Budget:</strong> " . safe_html($budget) . "</li>
            <li><strong>Pincode:</strong> " . safe_html($pincode) . "</li>
        </ul>
        <p>If you have any urgent queries, feel free to reach us directly at <a href='mailto:info@jomointeriors.in'>info@jomointeriors.in</a>.</p>
        <br>
        <p>Warm regards,<br><strong>JOMO Home Interiors Team</strong></p>
        <hr style='border: 0; border-top: 1px solid #eee; margin-top: 24px;'>
        <p style='font-size: 11px; color: #999;'>This is an automated confirmation. Please do not reply to this email.</p>
    </div>
</body>
</html>";

$user_sent = smtp_send($email, $user_subject, $user_body, 'info@jomointeriors.in');

ob_clean();
if ($admin_sent) {
    echo json_encode(['status' => 'success', 'message' => 'Success! Your enquiry has been sent.']);
} else {
    file_put_contents(
        __DIR__ . '/mail_error.log',
        date('Y-m-d H:i:s') . " | Contact form SMTP failed | $fullname | $email | user_mail=" . ($user_sent ? 'yes' : 'no') . "\n",
        FILE_APPEND
    );
    echo json_encode(['status' => 'success', 'message' => 'Enquiry received! Our team will contact you shortly.']);
}
?>