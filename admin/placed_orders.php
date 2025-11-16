<?php
include '../components/connect.php';
require '../components/send_status_update_email.php'; // Email sender function

session_start();
$admin_id = $_SESSION['admin_id'] ?? null;

if (!$admin_id) {
    header('location:admin_login.php');
    exit;
}

// Payment status update
if (isset($_POST['update_payment']) && isset($_POST['order_id']) && isset($_POST['payment_status'])) {
    $order_id = $_POST['order_id'];
    $payment_status = $_POST['payment_status'];

    $update = $conn->prepare("UPDATE orders SET payment_status = ? WHERE id = ?");
    $update->execute([$payment_status, $order_id]);

    $order = $conn->prepare("SELECT * FROM orders WHERE id = ?");
    $order->execute([$order_id]);
    $order_data = $order->fetch(PDO::FETCH_ASSOC);

    if ($order_data && isset($order_data['email'], $order_data['name'])) {
        sendStatusUpdateEmail($order_data['email'], $order_data['name'], 'Payment Status', $payment_status);
    }

    $message[] = 'Payment status updated!';
}

// Delivery status update
if (isset($_POST['update_delivery']) && isset($_POST['order_id']) && isset($_POST['delivery_status'])) {
    $order_id = $_POST['order_id'];
    $delivery_status = $_POST['delivery_status'];

    $update = $conn->prepare("UPDATE orders SET delivery_status = ? WHERE id = ?");
    $update->execute([$delivery_status, $order_id]);

    $order = $conn->prepare("SELECT * FROM orders WHERE id = ?");
    $order->execute([$order_id]);
    $order_data = $order->fetch(PDO::FETCH_ASSOC);

    if ($order_data && isset($order_data['email'], $order_data['name'])) {
        sendStatusUpdateEmail($order_data['email'], $order_data['name'], 'Delivery Status', $delivery_status);
    }

    $message[] = 'Delivery status updated!';
}

// Delete
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_order = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $delete_order->execute([$delete_id]);
    header('location:placed_orders.php');
    exit;
}
?>

<!-- HTML PART -->
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Placed Orders</title>
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php' ?>

<section class="placed-orders">
   <h1 class="heading">Placed Orders</h1>
   <div class="box-container">

   <?php
      $select_orders = $conn->prepare("SELECT * FROM orders");
      $select_orders->execute();
      if ($select_orders->rowCount() > 0) {
         while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
   ?>
   <div class="box">
      <p> User Id : <span><?= htmlspecialchars($fetch_orders['user_id']); ?></span> </p>
      <p> Placed on : <span><?= htmlspecialchars($fetch_orders['placed_on']); ?></span> </p>
      <p> Name : <span><?= htmlspecialchars($fetch_orders['name']); ?></span> </p>
      <p> Email : <span><?= htmlspecialchars($fetch_orders['email']); ?></span> </p>
      <p> Number : <span><?= htmlspecialchars($fetch_orders['number']); ?></span> </p>
      <p> Address : <span><?= htmlspecialchars($fetch_orders['address']); ?></span> </p>
      <p> Total products : <span><?= htmlspecialchars($fetch_orders['total_products']); ?></span> </p>
      <p> Total price : <span>Rs. <?= htmlspecialchars($fetch_orders['total_price']); ?>/-</span> </p>
      <p> Payment method : <span><?= htmlspecialchars($fetch_orders['method']); ?></span> </p>

      <!-- Payment Status -->
      <form action="" method="POST">
         <input type="hidden" name="order_id" value="<?= htmlspecialchars($fetch_orders['id']); ?>">
         <select name="payment_status" class="drop-down" required>
            <option selected disabled><?= htmlspecialchars($fetch_orders['payment_status']); ?></option>
            <option value="pending">Pending</option>
            <option value="completed">Completed</option>
            <option value="cancelled,Amount Refund">Cancelled,Amount Refund</option>
         </select>
         <input type="submit" name="update_payment" value="Update Payment" class="btn">
      </form>

      <!-- Delivery Status -->
      <form action="" method="POST" style="margin-top: 10px;">
         <input type="hidden" name="order_id" value="<?= htmlspecialchars($fetch_orders['id']); ?>">
         <select name="delivery_status" class="drop-down" required>
            <option selected disabled><?= htmlspecialchars($fetch_orders['delivery_status']); ?></option>
            <option value="preparing">Preparing</option>
            <option value="on the way">On The Way</option>
            <option value="delivered">Delivered</option>
            <option value="🚫order cancelled"> 🚫Order Cancelled</option>
         </select>
         <input type="submit" name="update_delivery" value="Update Delivery" class="btn">
      </form>

      <!-- Delete -->
      <a href="placed_orders.php?delete=<?= htmlspecialchars($fetch_orders['id']); ?>" class="delete-btn" onclick="return confirm('Delete this order?');">Delete</a>
   </div>
   <?php } } else {
      echo '<p class="empty">No orders placed yet!</p>';
   } ?>
   </div>
</section>
</body>
</html>