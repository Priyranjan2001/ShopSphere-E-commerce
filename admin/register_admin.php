<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
   exit;
}

// Check if there are already two admins
$check_admin_count = $conn->prepare("SELECT COUNT(*) FROM `admin`");
$check_admin_count->execute();
$total_admins = $check_admin_count->fetchColumn();

if ($total_admins >= 2) {
   echo '<script>alert("Only two admins allowed."); window.location.href = "admin_accounts.php";</script>';
   exit;
}

if (isset($_POST['submit'])) {

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);

   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name = ?");
   $select_admin->execute([$name]);

   if ($select_admin->rowCount() > 0) {
      $message = ['Username already taken!']; // Ensure this is an array
   } else {
      if ($pass != $cpass) {
         $message = ['Confirm password does not match!']; // Ensure this is an array
      } else {
         $insert_admin = $conn->prepare("INSERT INTO `admin`(name, password) VALUES(?,?)");
         $insert_admin->execute([$name, $cpass]);
         $message = ['New admin registered successfully!']; // Ensure this is an array
      }
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register admin</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="form-container">

   <form action="" method="post">
      <h3>register new admin</h3>
      
      <?php
      if (isset($message) && is_array($message)) {
         foreach ($message as $msg) {
            echo '<div class="message">' . $msg . '</div>';
         }
      }
      ?>
      
      <input type="text" name="name" maxlength="20" required placeholder="enter your username" class="box">
      <input type="password" name="pass" maxlength="20" required placeholder="enter your password" class="box">
      <input type="password" name="cpass" maxlength="20" required placeholder="confirm your password" class="box">
      <input type="submit" value="register now" class="btn" name="submit">
   </form>

</section>

<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>