<?php
session_start();

include 'kantandb_connect.php';

//enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$cart = $_SESSION['cart'] ?? [];
$subtotal = 0;

foreach ($cart as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

$shipping_fee = 50; //example flat rate for both standard and priority
$total = $subtotal + $shipping_fee;

//retrieve checkout form data
$address = $_POST['address'] ?? '';
$payment_method = $_POST['payment_method'] ?? '';
$delivery_option = $_POST['delivery_option'] ?? '';
$notes = $_POST['notes'] ?? '';

$username = $_SESSION['username'] ?? '';
if (!$username) {
    die("User not logged in.");
}

//get user ID
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("User not found.");
}
$user = $result->fetch_assoc();
$user_id = $user['id'];

//insert into orders table
$stmt = $conn->prepare("INSERT INTO orders (user_id, address, payment_method, delivery_option, notes_to_seller, total) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("issssd", $user_id, $address, $payment_method, $delivery_option, $notes, $total);
if (!$stmt->execute()) {
    die("Order insert failed: " . $stmt->error);
}
$order_id = $conn->insert_id;

//insert each item into order_items
foreach ($cart as $item) {
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_name, price, quantity) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isdi", $order_id, $item['name'], $item['price'], $item['quantity']);
    if (!$stmt->execute()) {
        die("Order item insert failed: " . $stmt->error);
    }
}

//clear cart
$stmt = $conn->prepare("DELETE FROM cart_items WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
unset($_SESSION['cart']);
?>

<!DOCTYPE html>
<html lang = "en">
<head>
    <meta charset = "UTF-8">
    <title>Order Confirmation</title>
    <link rel = "stylesheet" href="ls-userdashboard.css">
</head>
<body>
    <header class = "navbar">
        <div class = "nav-left">
            <img src = "kantanshoplogo.png" alt="Logo" class="logo">
        </div>
        <div class = "nav-right">
            <a href = "userhomepage.php">Home</a>
        </div>
    </header>

    <div class = "confirmation-box">
            <h2>Thank You! Your Order Has Been Placed</h2>
    </div>

    <div class = "section">
    <h3>Items Ordered</h3>
    <?php foreach ($cart_items as $item): ?>
        <p>
            <?= htmlspecialchars($item['product_name']) ?> - ₱<?= number_format($item['price'], 2) ?> × <?= $item['quantity'] ?><br>
            <strong>Note:</strong> <?= htmlspecialchars($item['note_to_seller']) ?: 'None' ?>
        </p>

        <div class = "confirmation-box">
            <h2>Thank You! Your Order Has Been Placed</h2>
        </div>
        <div class="section">
            <h3>Shipping Information</h3>
            <p><strong>Address:</strong> <?= htmlspecialchars($address) ?></p>
            <p><strong>Delivery Option:</strong> <?= htmlspecialchars(ucfirst($delivery_option)) ?></p>
        </div>

        <div class="section">
            <h3>Payment Method</h3>
            <p><?= htmlspecialchars(ucfirst($payment_method)) ?></p>
        </div>

        <div class="section">
            <h3>Notes to Seller</h3>
            <p><?= nl2br(htmlspecialchars($notes)) ?: 'None' ?></p>
        </div>

        <div class="section">
            <h3>Items Ordered</h3>
            <?php foreach ($cart as $item): ?>
                <p><?= htmlspecialchars($item['name']) ?> - ₱<?= number_format($item['price'], 2) ?> × <?= $item['quantity'] ?></p>
            <?php endforeach; ?>
        </div>

        <div class="section total">
            Total Paid: ₱<?= number_format($total, 2) ?>
        </div>
    <?php endforeach; ?>
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