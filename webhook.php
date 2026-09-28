<?php

function send_to_webhook($data) {
    $env_file = __DIR__ . '/.env';
    if (!file_exists($env_file)) {
        return false;
    }
    
    $env = parse_ini_file($env_file);
    if (!$env || !isset($env['WEBHOOK_URL']) || !isset($env['WEBHOOK_KEY'])) {
        return false;
    }

    $webhook_url = $env['WEBHOOK_URL'];
    $webhook_key = $env['WEBHOOK_KEY'];

    $user_column_data = [];
    foreach ($data as $key => $value) {
        $user_column_data[] = [
            "column_id" => $key,
            "string_value" => (string)$value
        ];
    }

    $payload = [
        "key" => $webhook_key,
        "user_column_data" => $user_column_data
    ];

    $ch = curl_init($webhook_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'User-Agent: JomoInteriors/1.0'
    ]);
    
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5); // 5 seconds max execution time
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); // 5 seconds max connection time
    
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        file_put_contents(__DIR__ . '/mail_error.log', date('Y-m-d H:i:s') . " | Webhook cURL Error: $error_msg\n", FILE_APPEND);
    }
    
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code != 200) {
        file_put_contents(__DIR__ . '/mail_error.log', date('Y-m-d H:i:s') . " | Webhook HTTP Error: $http_code | Response: $response\n", FILE_APPEND);
    } else {
        file_put_contents(__DIR__ . '/webhook_success.log', date('Y-m-d H:i:s') . " | Webhook Success | Response: $response\n", FILE_APPEND);
    }

    return $http_code == 200;
}
