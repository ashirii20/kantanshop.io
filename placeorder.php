<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = $_POST['address'] ?? '';
    $payment_method = $_POST['payment_method'] ?? '';
    $delivery_option = $_POST['delivery_option'] ?? '';
    $notes = $_POST['notes'] ?? '';
    $total = $_POST['total'] ?? 0;
    $cart = $_SESSION['cart'] ?? [];

    // Clear cart after order
    unset($_SESSION['cart']);
} else {
    header("Location: checkout.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
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
    <div class="confirmation-box">
        <h2>Thank You! Your Order Has Been Placed</h2>

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
    </div>
</body>
</html>