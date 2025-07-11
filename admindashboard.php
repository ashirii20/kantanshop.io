<?php
session_start();

include 'kantandb_connect.php';
?>

<!DOCTYPE html>
<html>

    <head>
        <title>Welcome</title>
        <link rel = "stylesheet" href = "ls-userdashboard.css">
    </head>

    <body>
        <div class="page-content">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['admin1']); ?>!</h2>
            <a href = "logout.php" class = "cta-button">Log Out</a>
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