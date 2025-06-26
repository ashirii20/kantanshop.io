<?php
session_start();
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

//hardcoded login
if (trim ($username) === 'user' && ($password) === 'userpw123') {
    $_SESSION['user'] = $username;
    header('Location: userdashboard.php');
    exit;
}

else {
    echo "
    <!DOCTYPE html>
    <html>

    <head>
    <title>Login Failed</title>
    <link rel = 'stylesheet' href = 'finalprojstyle.css'>
    </head>

    <body>
      <div class = 'page-content'>
        <h2>Login Failed</h2>
        <p>Invalid login credentials. You entered:<br>
           Username: <strong>$username</strong><br>
           Password: <strong>$password</strong>
        </p>
        <a href = 'loginsignup.html' class = 'cta-button'>Back to Login</a>
      </div>
    </body>
    </html>";
}
?>