<?php
// C:\xampp\htdocs\food website backend\reset_password.php

require_once __DIR__ . '/components/connect.php';
session_start();

// no token → redirect to login
if (empty($_GET['token'])) {
    header('Location: login.php');
    exit;
}

$token = $_GET['token'];

if (isset($_POST['reset_pass'])) {
    $pass   = sha1($_POST['pass']);
    $c_pass = sha1($_POST['c_pass']);

    if ($pass !== $c_pass) {
        $message = 'Passwords do not match.';
    } else {
        // verify token + not expired
        $stmt = $conn->prepare(
            "SELECT id 
             FROM users 
             WHERE reset_token = ? 
               AND reset_expires > NOW()"
        );
        $stmt->execute([$token]);

        if ($stmt->rowCount() === 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // update password + clear token
            $upd = $conn->prepare(
                "UPDATE users 
                 SET password = ?, 
                     reset_token = NULL, 
                     reset_expires = NULL 
                 WHERE id = ?"
            );
            $upd->execute([$pass, $user['id']]);

            $message = 'Password reset successful. <a href="login.php">Login now</a>';
        } else {
            $message = 'Invalid or expired token.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .msg {
        color: red;
        font-family: inherit;
        font: 20px Arial, sans-serif;
        font-weight: 500;
        margin: 10px 0;
        text-align: center;
    }

    .msg a {
        color: #FFD700; /* Yellow link */
        text-decoration: underline;
    }
  </style>
</head>
<body>

  <?php include __DIR__ . '/components/user_header.php'; ?>

  <section class="form-container">
    <form method="post" action="">
      <h3>Reset Password</h3>

      <?php
        if (!empty($message)) {
            echo '<div class="msg">' . $message . '</div>';
        }
      ?>

      <input 
        type="password" 
        name="pass" 
        required 
        placeholder="New password" 
        class="box">
      <input 
        type="password" 
        name="c_pass" 
        required 
        placeholder="Confirm password" 
        class="box">
      <input 
        type="submit" 
        name="reset_pass" 
        value="Reset Password" 
        class="btn">
    </form>
  </section>

  <?php include __DIR__ . '/components/footer.php'; ?>
  <script src="js/script.js"></script>
</body>
</html>
