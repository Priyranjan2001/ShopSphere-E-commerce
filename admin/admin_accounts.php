<?php
include '../components/connect.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
   exit;
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_admin = $conn->prepare("DELETE FROM `admin` WHERE id = ?");
   $delete_admin->execute([$delete_id]);
   // Reset auto increment after delete
   $conn->exec("ALTER TABLE admin AUTO_INCREMENT = 1");
   header('location:admin_accounts.php');
   exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admins Account</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="accounts">

   <h1 class="heading">Admins Account</h1>

   <div class="box-container">

   <div class="box">
      <p>Register New Admin</p>
      <a href="register_admin.php" class="option-btn">Register</a>
   </div>

   <?php
      $select_account = $conn->prepare("SELECT * FROM `admin` ORDER BY id ASC");
      $select_account->execute();
      if($select_account->rowCount() > 0){
         while($fetch_accounts = $select_account->fetch(PDO::FETCH_ASSOC)){  
   ?>
   <div class="box">
      <p> Admin ID : <span><?= $fetch_accounts['id']; ?></span> </p>
      <p> Username : <span><?= htmlspecialchars($fetch_accounts['name']); ?></span> </p>
      <div class="flex-btn">
         <a href="admin_accounts.php?delete=<?= $fetch_accounts['id']; ?>" class="delete-btn" onclick="return confirm('Delete this account?');">Delete</a>
         <a href="update_profile.php?id=<?= $fetch_accounts['id']; ?>" class="option-btn">Update</a>
      </div>
   </div>
   <?php
      }
   } else {
      echo '<p class="empty">No accounts available</p>';
   }
   ?>

   </div>

</section>

<script src="../js/admin_script.js"></script>

</body>
</html>