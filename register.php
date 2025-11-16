<?php

include 'components/connect.php';
include 'components/phpmailer_config.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
   $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   // 1. Name validation (only letters and spaces)
   if(!preg_match("/^[a-zA-Z\s]+$/", $name)){
      $message[] = 'Name must contain only letters and spaces!';
   }
   // 2. Email validation (must end with @gmail.com)
   elseif(!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/@gmail\.com$/', $email)){
      $message[] = 'Email must be a valid Gmail address (example@gmail.com)!';
   }
   // 3. Mobile number validation (exactly 10 digits)
   elseif(!preg_match('/^[0-9]{10}$/', $number)){
      $message[] = 'Phone number must be exactly 10 digits!';
   }
   else{
      // Check if email or number already exists
      $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? OR number = ?");
      $select_user->execute([$email, $number]);
      $row = $select_user->fetch(PDO::FETCH_ASSOC);

      if($select_user->rowCount() > 0){
         $message[] = 'Email or number already exists!';
      }else{
         if($pass != $cpass){
            $message[] = 'Confirm password not matched!';
         }else{
            // Insert new user
            $insert_user = $conn->prepare("INSERT INTO `users`(name, email, number, password) VALUES(?,?,?,?)");
            $insert_user->execute([$name, $email, $number, $cpass]);

            $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
            $select_user->execute([$email, $pass]);
            $row = $select_user->fetch(PDO::FETCH_ASSOC);

            if($select_user->rowCount() > 0){
               $_SESSION['user_id'] = $row['id'];
               sendRegistrationSuccessEmail($email, $name); // ✅ Send email
               header('location:home.php');
               exit;
            }
         }
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
   <title>ShopSphere | Register</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="form-container">
   <form action="" method="post" onsubmit="return validateForm()">
      <h3>register now</h3>
      <!-- <?php
         if(isset($message)){
            foreach($message as $msg){
               echo '<div class="message">'.$msg.'</div>';
            }
         }
      ?> -->
      <input type="text" name="name" required placeholder="Enter your name" class="box" maxlength="50">
      <input type="email" name="email" required placeholder="Enter your email" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="number" name="number" required placeholder="Enter your number" class="box" min="0" max="9999999999" maxlength="10">
      <input type="password" name="pass" required placeholder="Enter your password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" required placeholder="Confirm your password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Register Now" name="submit" class="btn">
      <p>Already have an account? <a href="login.php">Login now</a></p>
   </form>
</section>

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<!-- Custom client-side validation -->
<script>
function validateForm(){
   let name = document.querySelector('input[name="name"]').value.trim();
   let email = document.querySelector('input[name="email"]').value.trim();
   let number = document.querySelector('input[name="number"]').value.trim();

   // Name validation
   if(!/^[a-zA-Z\s]+$/.test(name)){
      alert("Name must contain only letters and spaces!");
      return false;
   }

   // Email validation
   if(!/^[a-zA-Z0-9._%+-]+@gmail\.com$/.test(email)){
      alert("Email must be a valid Gmail address (example@gmail.com)!");
      return false;
   }

   // Number validation
   if(!/^\d{10}$/.test(number)){
      alert("Phone number must be exactly 10 digits!");
      return false;
   }

   return true; // sab sahi hai, form submit hoga
}
</script>

</body>
</html>