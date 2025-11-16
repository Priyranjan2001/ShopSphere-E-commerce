<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:home.php');
}

date_default_timezone_set('Asia/Kolkata');

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Orders</title>

   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="css/style.css">

   <style>
   .timeline {
      display: flex;
      justify-content: space-between;
      position: relative;
      margin-top: 20px;
      padding: 0 10px;
   }

   .step {
      text-align: center;
      flex: 1;
      position: relative;
   }

   .step::after {
      content: '';
      position: absolute;
      top: 18px;
      left: 50%;
      height: 4px;
      width: 100%;
      background-color: #ccc;
      z-index: -1;
      transform: translateX(-50%);
   }

   .step:first-child::after {
      left: 50%;
      width: 50%;
   }

   .step:last-child::after {
      width: 50%;
   }

   .step.active::after {
      background-color: #4CAF50;
   }

   .circle {
      width: 30px;
      height: 30px;
      line-height: 30px;
      border-radius: 50%;
      background: #ccc;
      margin: 0 auto;
      font-size: 18px;
      z-index: 2;
   }

   .step.active .circle {
      background-color: #4CAF50;
      color: white;
   }

   .label {
      margin-top: 8px;
      font-size: 14px;
      color: #444;
   }

   .bike-emoji {
      font-size: 22px;
      animation: none;
   }

   .bike-emoji.moving {
      animation: moveBike 1s ease-in-out infinite alternate;
   }

   @keyframes moveBike {
      0% { transform: translateX(-5px); }
      100% { transform: translateX(5px); }
   }

   .cancelled .circle {
      background-color: red;
      color: white;
   }

   .cancelled .label {
      color: red;
   }
   </style>
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<div class="heading">
   <h3>Orders</h3>
   <p><a href="home.php">Home</a> <span> / Orders</span></p>
</div>

<section class="orders">
   <h1 class="title">Your Orders</h1>

   <div class="box-container">
   <?php
      if($user_id == ''){
         echo '<p class="empty">Please login to see your orders</p>';
      }else{
         $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ? ORDER BY id DESC");
         $select_orders->execute([$user_id]);
         if($select_orders->rowCount() > 0){
            while($order = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p>Placed on : <span><?= $order['placed_on']; ?></span></p>
      <p>Name : <span><?= $order['name']; ?></span></p>
      <p>Email : <span><?= $order['email']; ?></span></p>
      <p>Number : <span><?= $order['number']; ?></span></p>
      <p>Address : <span><?= $order['address']; ?></span></p>
      <p>Payment method : <span><?= $order['method']; ?></span></p>
      <p>Your orders : <span><?= $order['total_products']; ?></span></p>
      <p>Total price : <span>Rs. <?= $order['total_price']; ?>/-</span></p>
      <p>Payment status : 
         <span style="color:<?= $order['payment_status'] == 'pending' ? 'red' : 'green'; ?>">
            <?= ucfirst($order['payment_status']); ?>
         </span>
      </p>
      <p>Delivery status : 
         <span style="color:
            <?= 
               $order['delivery_status'] == 'delivered' ? 'green' : 
               ($order['delivery_status'] == 'on the way' ? 'orange' : 
               ($order['delivery_status'] == 'cancelled' ? 'red' : 'gray')); ?>">
            <?= ucfirst($order['delivery_status']); ?>
         </span>
      </p>

      <!-- 🚚 Delivery Timeline -->
      <?php if ($order['delivery_status'] == '🚫order cancelled'): ?>
         <div class="timeline">
            <div class="step cancelled">
               <div class="circle">🚫</div>
               <div class="label">Cancelled</div>
            </div>
         </div>
      <?php else: ?>
         <div class="timeline">
            <!-- Step 1 -->
            <div class="step <?= in_array($order['delivery_status'], ['on the way', 'delivered']) ? 'active' : '' ?>">
               <div class="circle">✅</div>
               <div class="label">Order Placed</div>
            </div>

            <!-- Step 2 -->
            <div class="step <?= in_array($order['delivery_status'], ['on the way', 'delivered']) ? 'active' : '' ?>">
               <div class="circle bike-emoji <?= $order['delivery_status'] == 'on the way' ? 'moving' : '' ?>">🛵</div>
               <div class="label">On the Way</div>
            </div>

            <!-- Step 3 -->
            <div class="step <?= $order['delivery_status'] == 'delivered' ? 'active' : '' ?>">
               <div class="circle">📦</div>
               <div class="label">Delivered</div>
            </div>
         </div>
      <?php endif; ?>

      <a href="generate_invoice.php?order_id=<?= $order['id']; ?>" class="btn" target="_blank">Download Invoice</a>
   </div>
   <?php
            }
         } else {
            echo '<p class="empty">No orders placed yet!</p>';
         }
      }
   ?>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
