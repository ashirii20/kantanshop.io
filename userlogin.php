<?php
session_start();

include 'kantandb_connect.php';

$username = $_POST['username'];
$password = $_POST['password'];

//prepare a SELECT query to check credentials
$sql = "SELECT * FROM users WHERE username = ? AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    //login successful
    $_SESSION['username'] = $username;
    session_regenerate_id(true);
    header('Location: userprofile.php');
    exit;
} else {
    //login failed
    echo "
    <!DOCTYPE html>
    <html>
    <head>
        <title>Login Failed</title>
        <link rel='stylesheet' href='ls-userdashboard.css'>
    </head>
    <body>
        <div class='page-content'>
            <h2>Login Failed</h2>
            <p>Invalid login credentials. You entered:<br>
               Username: <strong>$username</strong><br>
               Password: <strong>$password</strong>
            </p>
            <a href='loginsignup.html' class='cta-button'>Back to Login</a>
        </div>
    </body>
    </html>";
}

$stmt->close();
$conn->close();
?>