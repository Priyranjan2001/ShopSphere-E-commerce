<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Make sure path is correct

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
   exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
   header('location:messages.php');
   exit;
}

$message_id = $_GET['id'];

// Fetch the message once
$select_message = $conn->prepare("SELECT * FROM `messages` WHERE id = ?");
$select_message->execute([$message_id]);
$message_data = $select_message->fetch(PDO::FETCH_ASSOC);

// If message not found, redirect
if (!$message_data) {
   header('location:messages.php');
   exit;
}

if (isset($_POST['send_reply'])) {
   $reply = htmlspecialchars(trim($_POST['reply']));
   $user_email = $message_data['email'];
   $user_name = $message_data['name'];

   // Email content
   $body = "Hello $user_name,\n\n";
   $body .= "This is a reply to your message:\n";
   $body .= "---------------------------------\n";
   $body .= $message_data['message'] . "\n";
   $body .= "---------------------------------\n\n";
   $body .= "Reply from Admin:\n$reply\n\n";
   $body .= "Best regards,\nAdmin Team";

   $mail = new PHPMailer(true);

   try {
      // Server settings
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Username   = 'shopsphere2001@gmail.com'; // Your Gmail
      $mail->Password   = 'pdgd mknx qhlr smir'; // Your Gmail App Password
      $mail->SMTPSecure = 'tls';
      $mail->Port = 587;

      // Recipients
      $mail->setFrom('your_email@gmail.com', 'Admin');
      $mail->addAddress($user_email, $user_name);

      // Content
      $mail->isHTML(false);
      $mail->Subject = 'Reply to your message at OurSite';
      $mail->Body    = $body;

      $mail->send();

      // Save reply to DB
      $insert_reply = $conn->prepare("INSERT INTO `replies`(message_id, reply_text, admin_id) VALUES(?,?,?)");
      $insert_reply->execute([$message_id, $reply, $admin_id]);

      $success_msg = "Reply sent successfully to {$user_email}!";
   } catch (Exception $e) {
      $error_msg = "Mailer Error: " . $mail->ErrorInfo;
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Reply to Message</title>
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="form-container">
   <form action="" method="POST">
      <h3>Reply to: <?= htmlspecialchars($message_data['name']); ?></h3>
      <p><strong>Message:</strong> <?= nl2br(htmlspecialchars($message_data['message'])); ?></p>
      <textarea name="reply" required placeholder="Type your reply..." class="box" cols="30" rows="5"></textarea>
      <input type="submit" name="send_reply" value="Send Reply" class="btn">
      <a href="messages.php" class="option-btn">Back</a>
      <?php if (isset($success_msg)) echo '<p class="success">' . $success_msg . '</p>'; ?>
      <?php if (isset($error_msg)) echo '<p class="error">' . $error_msg . '</p>'; ?>
   </form>
</section>

</body>
</html>
