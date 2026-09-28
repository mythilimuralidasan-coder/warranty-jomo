<?php
// Hostinger SMTP mailer – SSL port 465
function smtp_send(string $to, string $subject, string $html_body, string $reply_to = ''): bool
{
    $host      = 'smtp.hostinger.com';
    $port      = 465;
    $username  = 'enquiry@jomointeriors.in';
    $password  = 'jomoMH@2026';
    $from      = 'enquiry@jomointeriors.in';
    $from_name = 'JOMO Home Interiors';

    $fp = @stream_socket_client("ssl://{$host}:{$port}", $errno, $errstr, 30);
    if (!$fp) return false;

    $read = function () use ($fp): string {
        $resp = '';
        while (($line = fgets($fp, 512)) !== false) {
            $resp .= $line;
            if (isset($line[3]) && $line[3] === ' ') break;
        }
        return $resp;
    };

    $write = function (string $cmd) use ($fp): void {
        fwrite($fp, $cmd . "\r\n");
    };

    $ok = function (string $resp, ?int $expect = null): bool {
        $code = (int) substr($resp, 0, 3);
        return $expect !== null ? $code === $expect : ($code >= 200 && $code < 400);
    };

    if (!$ok($read())) { fclose($fp); return false; }

    $write("EHLO jomointeriors.in");
    if (!$ok($read())) { fclose($fp); return false; }

    $write("AUTH LOGIN");
    if (!$ok($read())) { fclose($fp); return false; }

    $write(base64_encode($username));
    if (!$ok($read())) { fclose($fp); return false; }

    $write(base64_encode($password));
    if (!$ok($read(), 235)) { fclose($fp); return false; }

    $write("MAIL FROM: <{$from}>");
    if (!$ok($read())) { fclose($fp); return false; }

    $write("RCPT TO: <{$to}>");
    if (!$ok($read())) { fclose($fp); return false; }

    $write("DATA");
    if (!$ok($read())) { fclose($fp); return false; }

    $rt_line = $reply_to ? "Reply-To: {$reply_to}\r\n" : '';
    $encoded_subject = '=?UTF-8?B?' . base64_encode($subject) . '?=';

    $headers = "From: {$from_name} <{$from}>\r\n"
             . "To: {$to}\r\n"
             . "Subject: {$encoded_subject}\r\n"
             . "MIME-Version: 1.0\r\n"
             . "Content-Type: text/html; charset=UTF-8\r\n"
             . $rt_line;

    $write($headers . "\r\n" . $html_body . "\r\n.");
    $sent = $ok($read(), 250);

    $write("QUIT");
    fclose($fp);
    return $sent;
}
