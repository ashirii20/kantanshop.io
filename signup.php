<?php
session_start();
$username = $_POST['new_username'];
$email = $_POST['new_email'];
$password = $_POST['new_password'];

//hardcoded sign up user, no database yet
if (trim($username) === 'user') {
    $_SESSION['user'] = $username;
    header('Location: userdashboard.php');
    exit;
}

else{
    echo "
    <!DOCTYPE html>
    <html>

    <head>
        <title>Sign Up Failed</title>
        <link rel = 'stylesheet' href = 'finalprojstyle.css'>
    </head>

    <body>
        <div class = 'page-content'>
            <h2>Sign Up Failed</h2>
            <p>Username is already taken or invalid. Please try again.</p>
            <a href = 'loginsignup.html' class = 'cta-button'>Back to Sign Up</a>
        </div>
    </body>

    </html>
    ";
}
?>