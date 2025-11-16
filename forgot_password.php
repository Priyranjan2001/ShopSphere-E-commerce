<?php
// C:\xampp\htdocs\food website backend\forgot_password.php

// absolute includes so PHP never gets lost
require_once __DIR__ . '/components/connect.php';
require_once __DIR__ . '/components/send_reset_mail.php';
session_start();

if (isset($_POST['send_link'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // check user exists
    $stmt = $conn->prepare("SELECT id, name FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() === 1) {
        $user    = $stmt->fetch(PDO::FETCH_ASSOC);
        $token   = bin2hex(random_bytes(16));
        $expires = date('Y-m-d H:i:s', time() + 3600); // valid +1h

        // store token & expiry
        $upd = $conn->prepare(
            "UPDATE users 
             SET reset_token = ?, reset_expires = ? 
             WHERE id = ?"
        );
        $upd->execute([$token, $expires, $user['id']]);

        // send mail
        sendResetEmail($email, $user['name'], $token);
        $message = "A reset link has been sent to your email.";
    } else {
        $message = "No account found with that email.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <?php include __DIR__ . '/components/user_header.php'; ?>

  <section class="form-container">
    <form method="post" action="">
      <h3>Forgot Password</h3>
      <?php if (!empty($message)) echo "<p class='msg'>{$message}</p>"; ?>
      <input 
        type="email" 
        name="email" 
        required 
        placeholder="Enter your email" 
        class="box">
      <input 
        type="submit" 
        name="send_link" 
        value="Send Reset Link" 
        class="btn">
      <p><a href="login.php">Back to login</a></p>
    </form>
  </section>

  <?php include __DIR__ . '/components/footer.php'; ?>

  <!-- hide the loader from user_header.php -->
  <script src="js/script.js"></script>
</body>
</html>
