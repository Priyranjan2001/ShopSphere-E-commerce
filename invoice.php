<?php
include 'components/connect.php';
session_start();
date_default_timezone_set('Asia/Kolkata');

if (!isset($_SESSION['user_id']) || !isset($_SESSION['last_order_id'])) {
    header('location:home.php');
    exit;
}

$order_id = $_SESSION['last_order_id'];
$user_id = $_SESSION['user_id'];

$select_order = $conn->prepare("SELECT * FROM `orders` WHERE id = ? AND user_id = ?");
$select_order->execute([$order_id, $user_id]);

if ($select_order->rowCount() > 0) {
    $order = $select_order->fetch(PDO::FETCH_ASSOC);
} else {
    echo "No order found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Invoice - Order #<?= $order['id'] ?></title>
   <style>
      body {
         font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
         background-color: #f9f9f9;
         margin: 0;
         padding: 0;
         color: #333;
      }
      .invoice-container {
         max-width: 700px;
         margin: 50px auto;
         background: white;
         padding: 30px 40px;
         border-radius: 10px;
         box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
      }
      .invoice-header {
         text-align: center;
         margin-bottom: 30px;
      }
      .invoice-header h1 {
         margin: 0;
         color: #2c3e50;
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
      .buttons {
         text-align: center;
         margin-top: 30px;
      }
      .btn {
         display: inline-block;
         background-color: #2980b9;
         color: #fff;
         text-decoration: none;
         padding: 12px 25px;
         border-radius: 6px;
         margin: 0 10px;
         transition: background 0.3s ease;
      }
      .btn:hover {
         background-color: #3498db;
      }
      .btn.green {
         background-color: #27ae60;
      }
      @media print {
         .buttons {
            display: none;
         }
      }
   </style>
</head>
<body>

<div class="invoice-container">
   <div class="invoice-header">
      <h1>Invoice  ShopSphere </h1>
      <p>Order #<?= $order['id'] ?> | Printed on <?= date("d M Y, h:i A") ?></p>
   </div>

   <div class="thank-you">
      <h2>Thanks for choosing ShopSphere 🚀🍔🍟</h2>
      🎉 Thank you for your order!
   </div>

   <div class="invoice-section">
      <h3>Customer Details</h3>
      <p><strong>Name:</strong> <?= htmlspecialchars($order['name']) ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($order['email']) ?></p>
      <p><strong>Phone:</strong> <?= htmlspecialchars($order['number']) ?></p>
      <p><strong>Address:</strong> <?= htmlspecialchars($order['address']) ?></p>
   </div>

   <div class="invoice-section">
      <h3>Order Summary</h3>
      <p><strong>Products:</strong> <?= htmlspecialchars($order['total_products']) ?></p>
      <p><strong>Payment Method:</strong> <?= htmlspecialchars($order['method']) ?></p>
      <p><strong>Total Price:</strong> ₹<?= $order['total_price'] ?></p>
      <p><strong>Order Placed On:</strong> 
         <?php
            $placed_on_ts = strtotime($order['placed_on']);
            if ($placed_on_ts && $placed_on_ts > 0) {
               echo date("d M Y, h:i A", $placed_on_ts);
            } else {
               echo "Unknown";
            }
         ?>
      </p>
   </div>

   <div class="buttons">
      <a href="#" class="btn" onclick="window.print()">🖨️ Print Invoice</a>
      <a href="home.php" class="btn green">🏠 Back to Home</a>
   </div>
</div>

<script>
   // Auto redirect to home after 50 seconds
   setTimeout(() => {
      window.location.href = 'home.php';
   }, 50000);
</script>

</body>
</html>
