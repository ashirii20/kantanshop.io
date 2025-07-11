<?php
session_start();

include 'kantandb_connect.php';

$cart = $_SESSION['cart'] ?? [];
$subtotal = 0;

foreach ($cart as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

$shipping_fee = 50; //example flat rate for both standard and priority
$total = $subtotal + $shipping_fee;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="ls-userdashboard.css">
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

    <div class="checkout-container">
        <h2>Checkout Page</h2>

        <form action="placeorder.php" method="POST">
            <div class="cart-item">
                <h3>Cart Items</h3>
                <?php foreach ($cart as $productName => $item): ?>
                    <p>
                        <strong><?= htmlspecialchars($productName) ?></strong><br>
                        Price: ₱<?= number_format($item['price'], 2) ?><br>
                        Quantity: <?= $item['quantity'] ?>
                    </p>
                <?php endforeach; ?>

            </div>

            <div class="form-section">
                <h3>Shipping Information</h3>
                <label for="address">Address</label>
                <input type="text" name="address" placeholder="Enter your address" required>
            </div>

            <div class="form-section">
                <h3>Payment Method</h3>
                <label for="payment_method">Select Payment Method</label>
                <select name="payment_method" required>
                    <option value="debit">Debit</option>
                    <option value="credit">Credit</option>
                    <option value="cash">Cash</option>
                    <option value="ewallet">E-wallet</option>
                </select>
            </div>

            <div class="form-section">
                <h3>Delivery Option</h3>
                <label for="delivery_option">Select Delivery Option</label>
                <select name="delivery_option" required>
                    <option value="standard">Standard</option>
                    <option value="priority">Priority</option>
                </select>
            </div>

            <div class="form-section">
                <h3>Notes to Seller</h3>
                <label for="notes">Notes</label>
                <textarea name="notes" placeholder="User notes for the seller"></textarea>
            </div>

            <div class="summary">
                <p>Subtotal: ₱<?= number_format($subtotal, 2) ?></p>
                <p>Shipping Fee: ₱<?= number_format($shipping_fee, 2) ?></p>
                <p class="total">Total: ₱<?= number_format($total, 2) ?></p>
            </div>

            <input type="hidden" name="total" value="<?= $total ?>">
            <button type="submit" class="place-order-btn">Place Order</button>
        </form>
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