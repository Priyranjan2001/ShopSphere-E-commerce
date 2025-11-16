<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

function sendRegistrationSuccessEmail($email, $name) {
    $mail = new PHPMailer(true);

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'shopsphere2001@gmail.com'; // Your Gmail
        $mail->Password   = 'pdgd mknx qhlr smir'; // Your Gmail App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Email content
        $mail->setFrom('your_email@gmail.com', 'FastFeast');
        $mail->addAddress($email, $name);
        $mail->isHTML(true);
        $mail->Subject = 'Welcome to ShopSphere!';

        // Email body
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; color: #333; padding: 10px;'>
                <h2>Hi <b>$name</b>,</h2>
                <p>🎉 Welcome to <b>ShopSphere</b>! Your registration was <span style='color: green;'>successful</span>.</p>
                <p>Enjoy seamless shopping, fast delivery, and an amazing experience with us! 🛍️📦🛒</p>
                <br>
                <a href='http://localhost/food%20website%20backend/home.php' style='
                    display: inline-block;
                    padding: 10px 20px;
                    background-color: #28a745;
                    color: white;
                    text-decoration: none;
                    border-radius: 5px;
                    font-weight: bold;
                '>Go to Homepage</a>
                <br><br>
                <p>Thanks & Happy Shopping! 🛍️😊<br>– Team ShopSphere<br>PRIY_RANJAN</p>
            </div>
        ";

        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
