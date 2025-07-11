<?php
session_start();

include 'kantandb_connect.php';


$username = $_POST['username'] ?? '';
$newPassword = $_POST['new_password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';
$success = false;

if ($newPassword === $confirmPassword && $newPassword !== '') {
    if ($username !== '') {

        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
        $stmt->bind_param("ss", $newPassword, $username);

        if ($stmt->execute()) {
            $success = true;
        } else {
            echo "Error updating password: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error: No username found in session.";
    }
} else {
    echo "Error: Passwords do not match or are empty.";
}
$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset = "UTF-8">
  <title>Reset Password</title>
  <link rel = "stylesheet" href = "ls-userdashboard.css">
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

    <div class = "main-footer">
      <footer>
        <div class = "footer-content">
          <p>&copy; 2025 Kantan Shop. All rights reserved.</p>
        </div>
      </footer>
    </div>
</body>

</html>