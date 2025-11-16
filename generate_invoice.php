<?php
include 'components/connect.php';
session_start();
date_default_timezone_set('Asia/Kolkata');

if (!isset($_GET['order_id'])) {
    echo "Invalid request.";
    exit;
}

$order_id = $_GET['order_id'];

// Fetch order details
$select_order = $conn->prepare("SELECT * FROM `orders` WHERE id = ?");
$select_order->execute([$order_id]);
$order = $select_order->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "Invoice not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Invoice #<?= $order['id']; ?></title>
   <style>
      * {
         margin: 0;
         padding: 0;
         box-sizing: border-box;
         font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      }

      body {
         background: #f4f4f4;
         padding: 40px;
         color: #333;
      }

      .invoice-box {
         max-width: 800px;
         margin: auto;
         background: #fff;
         padding: 30px;
         border-radius: 12px;
         box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
      }

      .invoice-header {
         border-bottom: 2px solid #eee;
         padding-bottom: 20px;
         margin-bottom: 30px;
      }

      .invoice-header h1 {
         color: #2c3e50;
         font-size: 36px;
         text-align: center;
         margin-bottom: 10px;
      }

      .invoice-details {
         margin-bottom: 20px;
      }

      .info-section {
         display: flex;
         justify-content: space-between;
         margin-bottom: 20px;
      }

      .info-section div {
         width: 48%;
      }

      .info-section p {
         margin: 5px 0;
         font-size: 16px;
      }

      .summary-table {
         width: 100%;
         border-collapse: collapse;
         margin-top: 20px;
      }

      .summary-table th,
      .summary-table td {
         padding: 12px;
         border: 1px solid #ddd;
         text-align: left;
      }

      .summary-table th {
         background: #f8f8f8;
      }

      .total {
         font-size: 20px;
         font-weight: bold;
         text-align: right;
         margin-top: 30px;
      }

      .print-btn {
         display: block;
         margin: 30px auto 0;
         padding: 12px 30px;
         background-color: #3498db;
         color: white;
         text-decoration: none;
         border-radius: 8px;
         font-weight: bold;
         transition: 0.3s ease;
         text-align: center;
      }

      .print-btn:hover {
         background-color: #2980b9;
      }

      @media print {
         .print-btn {
            display: none;
         }

         body {
            background: white;
         }

         .invoice-box {
            box-shadow: none;
            border: none;
         }
      }
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
      <h1>Invoice</h1>
      <p>Order #<?= $order['id'] ?> | Printed on <?= date("d M Y, h:i A") ?></p>
   </div>

   <div class="thank-you">
      <h2>Thanks for choosing ShopSphere 🛍️📦🛒</h2>
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
        // Check if placed_on exists and is a valid timestamp
        $placed_on_ts = strtotime($order['placed_on']);
        if ($placed_on_ts && $placed_on_ts > 0) {
            echo date("d M Y, h:i A", $placed_on_ts);
        } else {
            echo "Not Available";
        }
    ?>
</p>

   </div>

   <div class="buttons">
      <a href="#" class="btn" onclick="window.print()">🖨️ Print Invoice</a>
      <a href="home.php" class="btn green">🏠 Back to Home</a>
   </div>
</div>

</body>
</html>
