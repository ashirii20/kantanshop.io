<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: loginsignup.html');
    exit;
}
?>

<!DOCTYPE html>
<html>

    <head>
        <title>Welcome</title>
        <link rel = "stylesheet" href = "finalprojstyle.css">
    </head>

    <body>
        <div class = "page-content">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h2>
            <a href = "logout.php" class="cta-button">Log Out</a>
        </div>
    </body>

</html>