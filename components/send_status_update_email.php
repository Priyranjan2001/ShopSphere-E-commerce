<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

function sendStatusUpdateEmail($toEmail, $toName, $type, $status) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'shopsphere2001@gmail.com'; // Your Gmail
        $mail->Password   = 'pdgd mknx qhlr smir'; // Your Gmail App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('your@gmail.com', 'ShopSphere');
        $mail->addAddress($toEmail, $toName);

        $mail->isHTML(true);
        $mail->Subject = "$type Updated - ShopSphere";

        $mail->Body = "
            Hi <strong>$toName</strong>,<br><br>
            Your <strong>$type</strong> has been updated to <strong style='color:green;'>$status</strong>.<br><br>
            Thank you for ordering with <strong>ShopSphere</strong>!<br><br>
            <em>- ShopSphere Team</em>
        ";

        $mail->send();
    } catch (Exception $e) {
        error_log("Mail error: " . $mail->ErrorInfo);
    }
}
