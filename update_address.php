<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:home.php');
   exit;
}

if(isset($_POST['submit'])){

   // Sanitize input values
   $flat = filter_var($_POST['flat'], FILTER_SANITIZE_STRING);
   $building = filter_var($_POST['building'], FILTER_SANITIZE_STRING);
   $area = filter_var($_POST['area'], FILTER_SANITIZE_STRING);
   $town = filter_var($_POST['town'], FILTER_SANITIZE_STRING);
   $city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
   $state = filter_var($_POST['state'], FILTER_SANITIZE_STRING);
   $country = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
   $pin_code = filter_var($_POST['pin_code'], FILTER_SANITIZE_STRING);

   // Server-side validation
   if (!preg_match('/^\d+$/', $flat) || !preg_match('/^\d+$/', $building)) {
      $message[] = 'Flat and building number must be digits only!';
   } elseif (!preg_match('/^[a-zA-Z0-9\s]+$/', $area)) {
      $message[] = 'Area name should contain only letters and numbers!';
   } elseif (!preg_match('/^[a-zA-Z\s]+$/', $town) || !preg_match('/^[a-zA-Z\s]+$/', $city) || 
             !preg_match('/^[a-zA-Z\s]+$/', $state) || !preg_match('/^[a-zA-Z\s]+$/', $country)) {
      $message[] = 'Town, city, state, and country should contain only letters!';
   } elseif (!preg_match('/^\d{6}$/', $pin_code)) {
      $message[] = 'Pin code must be exactly 6 digits!';
   } else {
      $address = "$flat, $building, $area, $town, $city, $state, $country - $pin_code";
      $update_address = $conn->prepare("UPDATE `users` SET address = ? WHERE id = ?");
      $update_address->execute([$address, $user_id]);
      $message[] = 'Address saved successfully!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>FastFeast | Update Address</title>

   <!-- font awesome cdn link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php' ?>

<section class="form-container">

   <form action="" method="post" onsubmit="return validateForm()">
      <h3>Your Address</h3>
      <input type="text" class="box" placeholder="Flat no." required maxlength="10" name="flat" pattern="\d+" title="Only digits allowed">
      <input type="text" class="box" placeholder="Building no." required maxlength="10" name="building" pattern="\d+" title="Only digits allowed">
      <input type="text" class="box" placeholder="Area name" required maxlength="50" name="area" pattern="[A-Za-z0-9\s]+" title="Letters and digits allowed">
      <input type="text" class="box" placeholder="Town name" required maxlength="50" name="town" pattern="[A-Za-z\s]+" title="Only letters allowed">
      <input type="text" class="box" placeholder="City name" required maxlength="50" name="city" pattern="[A-Za-z\s]+" title="Only letters allowed">
      <input type="text" class="box" placeholder="State name" required maxlength="50" name="state" pattern="[A-Za-z\s]+" title="Only letters allowed">
      <input type="text" class="box" placeholder="Country name" required maxlength="50" name="country" pattern="[A-Za-z\s]+" title="Only letters allowed">
      <input type="text" class="box" placeholder="Pin code" required name="pin_code" pattern="\d{6}" title="Pin code must be exactly 6 digits" maxlength="6" minlength="6">
      <input type="submit" value="Save Address" name="submit" class="btn">
   </form>

</section>

<?php include 'components/footer.php' ?>

<!-- custom js file link -->
<script src="js/script.js"></script>

<!-- Extra JS validation (client-side fallback) -->
<script>
function validateForm() {
   const flat = document.querySelector('[name="flat"]').value.trim();
   const building = document.querySelector('[name="building"]').value.trim();
   const area = document.querySelector('[name="area"]').value.trim();
   const pinCode = document.querySelector('[name="pin_code"]').value.trim();

   if (!/^\d+$/.test(flat)) {
      alert("Flat number should contain digits only.");
      return false;
   }

   if (!/^\d+$/.test(building)) {
      alert("Building number should contain digits only.");
      return false;
   }

   if (!/^[a-zA-Z0-9\s]+$/.test(area)) {
      alert("Area name can only contain letters and numbers.");
      return false;
   }

   if (!/^\d{6}$/.test(pinCode)) {
      alert("Pin code must be exactly 6 digits.");
      return false;
   }

   return true;
}
</script>

</body>
</html>