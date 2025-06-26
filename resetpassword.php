<?php
session_start();
$newPassword = $_POST['new_password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';
$success = false;

if ($newPassword === $confirmPassword && $newPassword !== '') {
    $success = true;
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset = "UTF-8">
  <title>Reset Password</title>
  <link rel = "stylesheet" href = "finalprojstyle.css">
</head>

<body>
  <div class="page-content">
    <?php if ($success): ?>
      <h2>Password Reset Successful</h2>
      <p>Your password has been reset successfully.</p>
      <a href="loginsignup.html" class="cta-button">Return to Login</a>
    <?php else: ?>
      <h2>Password Reset Failed</h2>
      <p>Passwords do not match. Please try again.</p>
      <a href="loginsignup.html" class="cta-button">Try Again</a>
    <?php endif; ?>
  </div>
</body>

</html>