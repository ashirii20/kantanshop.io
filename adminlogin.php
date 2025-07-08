<?php
session_start();
$adminUser = $_POST['admin_user'];
$adminPass = $_POST['admin_pass'];

//hardcoded admin user, no database yet
if (trim($adminUser) === 'admin1' && trim($adminPass) === 'admin123') {
    $_SESSION['admin1'] = $adminUser;
    header('Location: admindashboard.php');
    exit;
}

else{
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
    </body>

    </html>
    ";
}
?>
