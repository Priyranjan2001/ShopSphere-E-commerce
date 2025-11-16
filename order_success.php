<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv; // ✅ Add this line for Dotenv to work

require 'vendor/autoload.php'; // Loads PHPMailer and Dotenv

// Load environment variables from .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Assume $order_id is defined and DB connection ($conn) is valid
$select_order = $conn->prepare("SELECT * FROM `orders` WHERE id = ?");
$select_order->execute([$order_id]);
$order = $select_order->fetch(PDO::FETCH_ASSOC);

if ($order) {
    // Build invoice HTML
    $invoiceHTML = "
        <h2>Invoice for Order #{$order['id']}</h2>
        <p><strong>Placed on:</strong> " . date("d M Y, h:i A", strtotime($order['placed_on'])) . "</p>
        <p><strong>Name:</strong> {$order['name']}</p>
        <p><strong>Email:</strong> {$order['email']}</p>
        <p><strong>Phone:</strong> {$order['number']}</p>
        <p><strong>Address:</strong> {$order['address']}</p>
        <p><strong>Products:</strong> {$order['total_products']}</p>
        <p><strong>Payment Method:</strong> {$order['method']}</p>
        <p><strong>Total Price:</strong> ₹{$order['total_price']}</p>
    ";

    // Send email
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['SMTP_USERNAME'];
        $mail->Password   = $_ENV['SMTP_PASSWORD'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $_ENV['SMTP_PORT'];

        // Recipients
        $mail->setFrom($_ENV['SMTP_USERNAME'], $_ENV['SMTP_FROM_NAME']);
        $mail->addAddress($order['email'], $order['name']);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your Order Invoice - Order #' . $order['id'];
        $mail->Body    = $invoiceHTML;

        $mail->send();
        // Optional: log success
    } catch (Exception $e) {
        error_log("Invoice email could not be sent. Mailer Error: {$mail->ErrorInfo}");
    }
}
?>
