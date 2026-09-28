<?php
require_once __DIR__ . '/webhook.php';

$data = [
    "FULL_NAME" => "Test Name",
    "PHONE_NUMBER" => "9999999999",
    "EMAIL" => "test@example.com",
    "PROJECT_TYPE" => "Residential",
    "MESSAGE" => "Test Message"
];

$success = send_to_webhook($data);
echo "Webhook returned: " . ($success ? 'true' : 'false') . "\n";
echo "Error log contents:\n" . file_get_contents(__DIR__ . '/mail_error.log');
