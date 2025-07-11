<?php
session_start();

include 'kantandb_connect.php';

$username = $_POST['new_username'];
$email = $_POST['new_email'];
$password = $_POST['new_password'];

//prepare and bind to prevent SQL injection
$stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $email, $password);

//execute the statement
if ($stmt->execute()) {
    $_SESSION['username'] = $username;
    header('Location: userprofile.php');
    exit;
} else {
    echo "
    <!DOCTYPE html>
    <html>
    <head>
        <title>Sign Up Failed</title>
        <link rel='stylesheet' href='ls-userdashboard.css'>
    </head>
    <body>
        <div class='page-content'>
            <h2>Sign Up Failed</h2>
            <p>Username is already taken or invalid. Please try again.</p>
            <a href='loginsignup.html' class='cta-button'>Back to Sign Up</a>
        </div>
    </body>
    </html>
    ";
}

$stmt->close();
$conn->close();

session_regenerate_id(true);
?>