<?php
// Sends a service-request row to a Google Apps Script Web App bound to a
// Google Sheet. Configure SERVICE_SHEET_WEBHOOK_URL / SERVICE_SHEET_KEY in .env.

function send_to_service_sheet(array $data): bool
{
    $env_file = __DIR__ . '/.env';
    if (!file_exists($env_file)) {
        return false;
    }

    $env = parse_ini_file($env_file);
    if (!$env || empty($env['SERVICE_SHEET_WEBHOOK_URL'])) {
        return false;
    }

    $webhook_url = $env['SERVICE_SHEET_WEBHOOK_URL'];
    $webhook_key = $env['SERVICE_SHEET_KEY'] ?? '';

    $payload = array_merge(['key' => $webhook_key], $data);

    $ch = curl_init($webhook_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'User-Agent: JomoInteriors/1.0'
    ]);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 8);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 8);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        file_put_contents(__DIR__ . '/mail_error.log', date('Y-m-d H:i:s') . " | Service Sheet cURL Error: $error_msg\n", FILE_APPEND);
    }

    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code != 200) {
        file_put_contents(__DIR__ . '/mail_error.log', date('Y-m-d H:i:s') . " | Service Sheet HTTP Error: $http_code | Response: $response\n", FILE_APPEND);
        return false;
    }

    file_put_contents(__DIR__ . '/webhook_success.log', date('Y-m-d H:i:s') . " | Service Sheet Success | Response: $response\n", FILE_APPEND);
    return true;
}
