`<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['send'])){

   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
   $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
   $msg = filter_var($_POST['msg'], FILTER_SANITIZE_STRING);

   // Name validation (only alphabets and spaces)
   if(!preg_match("/^[a-zA-Z\s]+$/", $name)){
      $message[] = 'Name must contain only letters and spaces!';
   }
   // Email validation (must end with @gmail.com)
   elseif(!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/@gmail\.com$/', $email)){
      $message[] = 'Email must be a valid Gmail address (example@gmail.com)!';
   }
   // Number validation (exactly 10 digits)
   elseif(!preg_match('/^[0-9]{10}$/', $number)){
      $message[] = 'Phone number must be exactly 10 digits!';
   }
   else{
      // Check duplicate message
      $select_message = $conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND number = ? AND message = ?");
      $select_message->execute([$name, $email, $number, $msg]);

      if($select_message->rowCount() > 0){
         $message[] = 'You have already sent this message!';
      }else{
         $insert_message = $conn->prepare("INSERT INTO `messages`(user_id, name, email, number, message) VALUES(?,?,?,?,?)");
         $insert_message->execute([$user_id, $name, $email, $number, $msg]);
         $message[] = 'Message sent successfully!';
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
   <title>FastFeast | Contact</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<div class="heading">
   <h3>Contact Us</h3>
   <p><a href="home.php">Home</a> <span> / Contact</span></p>
</div>

<!-- contact section starts  -->

<section class="contact">

   <div class="row">

      <div class="image">
         <img src="images/contact-img.svg" alt="">
      </div>

      <form action="" method="post" onsubmit="return validateContactForm()">
         <h3>Tell us something!</h3>
         <!-- <?php
            if(isset($message)){
               foreach($message as $msg){
                  echo '<div class="message">'.$msg.'</div>';
               }
            }
         ?> -->
         <input type="text" name="name" maxlength="50" class="box" placeholder="Enter your name" required>
         <input type="text" name="number" maxlength="10" class="box" placeholder="Enter your number" required>
         <input type="email" name="email" maxlength="50" class="box" placeholder="Enter your email" required>
         <textarea name="msg" class="box" required placeholder="Enter your message" maxlength="500" cols="30" rows="10"></textarea>
         <input type="submit" value="Send Message" name="send" class="btn">
      </form>

   </div>

</section>

<!-- contact section ends -->

<!-- footer section starts  -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->

<!-- custom js file link  -->
<script src="js/script.js"></script>

<!-- Client-side validation -->
<script>
function validateContactForm(){
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

   return true; // sab sahi hai
}
</script>

</body>
</html>