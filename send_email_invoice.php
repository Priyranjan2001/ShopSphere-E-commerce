<?php
use Dompdf\Dompdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure Dompdf and PHPMailer are installed via Composer
include 'components/connect.php';

function sendEmailInvoice($order_id, $conn) {
    // Fetch order data
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([$order_id]);
    if ($stmt->rowCount() == 0) return false;

    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    // Format date
    $placed_on_ts = strtotime($order['placed_on']);
    $formatted_date = ($placed_on_ts && $placed_on_ts > 0) ? date("d M Y, h:i A", $placed_on_ts) : "Unknown";

    // HTML for the invoice (Dompdf compatible)
    $html = '
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            body {
                font-family: "DejaVu Sans", sans-serif;
                background-color: #f9f9f9;
                color: #333;
                padding: 30px;
            }
            .invoice-container {
                max-width: 700px;
                margin: 0 auto;
                background: white;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            .invoice-header {
                text-align: center;
                margin-bottom: 30px;
            }
            .thank-you {
                text-align: center;
                background-color: #eafaf1;
                padding: 15px;
                border-radius: 8px;
                margin-bottom: 30px;
                color: #27ae60;
                font-weight: bold;
                font-size: 18px;
            }
            .invoice-section {
                margin-bottom: 20px;
            }
            .invoice-section h3 {
                border-bottom: 2px solid #eee;
                padding-bottom: 5px;
                color: #34495e;
            }
            .invoice-section p {
                margin: 5px 0;
                line-height: 1.5;
            }
        </style>
    </head>
    <body>
    <div class="invoice-container">
        <div class="invoice-header">
            <h1>Invoice ShopSphere </h1>
            <p>Order #' . $order['id'] . ' | Printed on ' . date("d M Y, h:i A") . '</p>
        </div>

        <div class="thank-you">
            <h2>Thanks for choosing ShopSphere</h2>
            <p> Thank you for your order!</p>
        </div>

        <div class="invoice-section">
            <h3>Customer Details</h3>
            <p><strong>Name:</strong> ' . htmlspecialchars($order['name']) . '</p>
            <p><strong>Email:</strong> ' . htmlspecialchars($order['email']) . '</p>
            <p><strong>Phone:</strong> ' . htmlspecialchars($order['number']) . '</p>
            <p><strong>Address:</strong> ' . htmlspecialchars($order['address']) . '</p>
        </div>

        <div class="invoice-section">
            <h3>Order Summary</h3>
            <p><strong>Products:</strong> ' . htmlspecialchars($order['total_products']) . '</p>
            <p><strong>Payment Method:</strong> ' . htmlspecialchars($order['method']) . '</p>
            <p><strong>Total Price:</strong> ₹' . $order['total_price'] . '</p>
            <p><strong>Order Placed On:</strong> ' . $formatted_date . '</p>
        </div>
    </div>
    </body>
    </html>
    ';

    // Generate PDF
    $dompdf = new Dompdf(['defaultFont' => 'DejaVu Sans']);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $pdfContent = $dompdf->output();

    // Save to invoices/ folder
    $invoiceDir = __DIR__ . '/invoices/';
    if (!file_exists($invoiceDir)) {
        mkdir($invoiceDir, 0777, true);
    }

    $invoiceFilename = 'invoice_' . $order_id . '.pdf';
    $invoicePath = $invoiceDir . $invoiceFilename;
    file_put_contents($invoicePath, $pdfContent);

    // Send email with PHPMailer
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'shopsphere2001@gmail.com'; // Your Gmail
        $mail->Password   = 'pdgd mknx qhlr smir'; // Your Gmail App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Sender & Recipient
        $mail->setFrom('your@email.com', 'ShopSphere');
        $mail->addAddress($order['email'], $order['name']);

        // Attach PDF
        $mail->addAttachment($invoicePath, 'ShopSphere_Invoice_Order#' . $order_id . '.pdf');

        // Email body
        $mail->isHTML(true);
        $mail->Subject = 'Your Invoice - ShopSphere Order #' . $order_id;
        $mail->Body    = '
            Hi <strong>' . htmlspecialchars($order['name']) . '</strong>,<br><br>
            Thank you for your order on <strong>ShopSphere</strong>!<br>
            We\'re preparing your food with love and it will be at your doorstep soon! 🚀<br><br>
            Please find your invoice attached.<br><br>
            Enjoy your Products! 🛍️📱<br><br>
            <em>- The ShopSphere Team</em>
        ';

        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log('Mailer Error: ' . $mail->ErrorInfo);
        return false;
    }
}
?>
