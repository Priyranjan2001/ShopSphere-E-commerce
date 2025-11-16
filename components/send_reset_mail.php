<?php
// C:\xampp\htdocs\food website backend\components\send_reset_mail.php

date_default_timezone_set('Asia/Kolkata'); // ya your actual timezone


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// load PHPMailer classes via absolute path
require_once __DIR__ . '/../phpmailer/src/Exception.php';
require_once __DIR__ . '/../phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../phpmailer/src/SMTP.php';

function sendResetEmail(string $to_email, string $name, string $token): void {
    // build a reset link that works with spaces encoded
    $host    = $_SERVER['HTTP_HOST'];
    $baseUrl = "http://{$host}/food%20website%20backend";
    $resetLink = "{$baseUrl}/reset_password.php?token={$token}";

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';            // your SMTP host
        $mail->SMTPAuth   = true;
        $mail->Username   = 'shopsphere2001@gmail.com'; // Your Gmail
        $mail->Password   = 'pdgd mknx qhlr smir'; // Your Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('youremail@gmail.com', 'ShopSphere');
        $mail->addAddress($to_email, $name);

        $mail->isHTML(true);
        $mail->Subject = 'Reset Your ShopSphere Password';
        $mail->Body    = "
            <h2>Hello, {$name}</h2>
            <p>We received a request to reset your password. Click below within the next hour:</p>
            <p><a href=\"{$resetLink}\">Reset Your Password</a></p>
            <p>If you didn’t request this, please ignore this email.</p>
        ";

        $mail->send();
    } catch (Exception $e) {
        error_log('Reset email error: ' . $mail->ErrorInfo);
    }
}
