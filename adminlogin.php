<?php
session_start();
$adminUser = $_POST['admin_user'];
$adminPass = $_POST['admin_pass'];

include 'kantandb_connect.php';

$sql = "SELECT * FROM admins WHERE username = ? AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $adminUser, $adminPass);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $_SESSION['admin1'] = $adminUser;
    header('Location: admindashboard.php');
    exit;
} 

else {
    echo "
    <!DOCTYPE html>
    <html>

    <head>
        <title>Admin Login Failed</title>
        <link rel = 'stylesheet' href = 'ls-userdashboard.css'>
    </head>

    <body>
        <div class = 'page-content'>
            <h2>Admin Login Failed</h2>
            <p>Invalid admin credentials.</p>
            <a href = 'loginsignup.html' class = 'cta-button'>Back to Login</a>
        </div>

        <div class = 'main-footer'>
      <footer>
        <div class = 'footer-content'>
          <p>&copy; 2025 Kantan Shop. All rights reserved.</p>
        </div>
      </footer>
    </div>
    </body>
    </html>
    ";
}

session_regenerate_id(true);
?>