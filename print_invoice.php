<?php
include 'components/connect.php';
session_start();

if (!isset($_GET['order_id'])) {
    echo "Invalid request.";
    exit;
}

$order_id = $_GET['order_id'];

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
   </style>
</head>
<body>

<div class="invoice-box">
   <div class="invoice-header">
      <h1>Invoice</h1>
   </div>

   <div class="invoice-details">
      <div class="info-section">
         <div>
            <h3>Customer Info</h3>
            <p><strong>Name:</strong> <?= $order['name']; ?></p>
            <p><strong>Phone:</strong> <?= $order['number']; ?></p>
            <p><strong>Email:</strong> <?= $order['email']; ?></p>
            <p><strong>Address:</strong> <?= $order['address']; ?></p>
         </div>
         <div>
            <h3>Order Info</h3>
            <p><strong>Order ID:</strong> <?= $order['id']; ?></p>
            <p><strong>Date:</strong> <?= date('d M Y, h:i A', strtotime($order['placed_on'] ?? 'now')); ?></p>
            <p><strong>Payment Method:</strong> <?= $order['method']; ?></p>
         </div>
      </div>
   </div>

   <table class="summary-table">
      <tr>
         <th>Items</th>
         <td><?= $order['total_products']; ?></td>
      </tr>
      <tr>
         <th>Total Price</th>
         <td>Rs. <?= $order['total_price']; ?></td>
      </tr>
   </table>

   <p class="total">Grand Total: Rs. <?= $order['total_price']; ?></p>

   <a class="print-btn" onclick="window.print()">🖨️ Print Invoice</a>
</div>

</body>
</html>
