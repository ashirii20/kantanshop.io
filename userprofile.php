<?php
session_start();

include 'kantandb_connect.php';

//fallback values if session variables are not set
$username = $_SESSION['username'] ?? '';
$avatar = $_SESSION['avatar'] ?? 'placeholder.png';
?>

<!DOCTYPE html>
<html lang = "en">
<head>
  <meta charset = "UTF-8">
  <title>User Profile</title>
  <link rel = "stylesheet" href = "ls-userdashboard.css">
</head>
<body>
  <header class = "navbar">
    <div class = "nav-left">
      <img src = "kantanshoplogo.png" alt = "Logo" class = "logo">
    </div>
    <div class = "nav-right">
      <a href = "userhomepage.php">Home</a>
      <a href = "userprofile.php" class = "user-avatar-link">User</a>
      <form action="logout.php" method="POST" style="margin-left: 10px;">
        <button type="submit">Logout</button>
      </form>
  </header>

  <div class = "page-content">
    <h2>Welcome, <?= htmlspecialchars($username) ?>!</h2>
    <div class = "user-avatar" style = "width: 100px; height: 100px; margin: 20px auto; background-image: url('<?= htmlspecialchars($avatar) ?>');"></div>
    <p>This is your profile page.</p>
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