<?php
session_start();
date_default_timezone_set('Asia/Kolkata');

include 'components/connect.php';
require_once 'send_email_invoice.php'; // Only include once

if (!isset($_SESSION['user_id'])) {
    header('location:home.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user profile
$fetch_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$fetch_profile->execute([$user_id]);
$fetch_profile = $fetch_profile->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['submit'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $method = filter_var($_POST['method'], FILTER_SANITIZE_STRING);
    $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
    $total_products = $_POST['total_products'];
    $total_price = $_POST['total_price'];
    $placed_on = date('Y-m-d H:i:s');

    $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
    $check_cart->execute([$user_id]);

    if ($check_cart->rowCount() > 0) {
        if ($address == '') {
            $message[] = 'Please add your address!';
        } else {
            // Insert order
            $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES(?,?,?,?,?,?,?,?,?)");
            $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price, $placed_on]);

            // Get last order ID
            $last_order_id = $conn->lastInsertId();
            $_SESSION['last_order_id'] = $last_order_id;

            // Clear cart
            $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
            $delete_cart->execute([$user_id]);

            // ✅ Send email invoice only
            sendEmailInvoice($last_order_id, $conn);

            // Redirect to invoice page
            header("Location: invoice.php?order_id=$last_order_id");
            exit;
        }
    } else {
        $message[] = 'Your cart is empty!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<div class="heading">
   <h3>FastFeast | Checkout</h3>
   <p><a href="home.php">Home</a> <span> / Checkout</span></p>
</div>

<section class="checkout">
   <h1 class="title">Order Summary</h1>

   <form action="" method="post">
      <div class="cart-items">
         <h3>Cart Items</h3>
         <?php
            $grand_total = 0;
            $cart_items = [];
            $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $select_cart->execute([$user_id]);
            if ($select_cart->rowCount() > 0) {
               while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                  $cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['price'].' x '.$fetch_cart['quantity'].')';
                  $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
         ?>
         <p><span class="name"><?= $fetch_cart['name']; ?></span><span class="price">Rs.<?= $fetch_cart['price']; ?> x <?= $fetch_cart['quantity']; ?></span></p>
         <?php
               }
            } else {
               echo '<p class="empty">Your cart is empty!</p>';
            }
            $total_products = implode(' - ', $cart_items);
         ?>
         <p class="grand-total"><span class="name">Grand Total :</span><span class="price">Rs.<?= $grand_total; ?></span></p>
         <a href="cart.php" class="btn">View Cart</a>
      </div>

      <input type="hidden" name="total_products" value="<?= $total_products; ?>">
      <input type="hidden" name="total_price" value="<?= $grand_total; ?>">
      <input type="hidden" name="name" value="<?= $fetch_profile['name'] ?>">
      <input type="hidden" name="number" value="<?= $fetch_profile['number'] ?>">
      <input type="hidden" name="email" value="<?= $fetch_profile['email'] ?>">
      <input type="hidden" name="address" value="<?= $fetch_profile['address'] ?>">

      <div class="user-info">
         <h3>Your Info</h3>
         <p><i class="fas fa-user"></i><span><?= $fetch_profile['name'] ?></span></p>
         <p><i class="fas fa-phone"></i><span><?= $fetch_profile['number'] ?></span></p>
         <p><i class="fas fa-envelope"></i><span><?= $fetch_profile['email'] ?></span></p>
         <a href="update_profile.php" class="btn">Update Info</a>

         <h3>Delivery Address</h3>
         <p><i class="fas fa-map-marker-alt"></i><span><?php if ($fetch_profile['address'] == '') { echo 'please enter your address'; } else { echo $fetch_profile['address']; } ?></span></p>
         <a href="update_address.php" class="btn">Update Address</a>

         <select name="method" class="box" required>
            <option value="" disabled selected>Select payment method --</option>
            <option value="cash on delivery">Cash on Delivery</option>
            <option value="credit card">Credit Card</option>
            <option value="paytm">Paytm</option>
            <option value="Phone Pay">Phone Pay</option>
            <option value="paypal">Paypal</option>
         </select>

         <input type="submit" value="Place Order" class="btn <?php if ($fetch_profile['address'] == '') { echo 'disabled'; } ?>" style="width:100%; background:var(--red); color:var(--white);" name="submit">
      </div>
   </form>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>