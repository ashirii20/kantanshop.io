<?php
session_start();

include 'kantandb_connect.php';

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

//load products
$products = json_decode(file_get_contents('products.json'), true);
$productName = $_POST['product'] ?? '';
$action = $_POST['action'] ?? '';
$quantity = intval($_POST['quantity'] ?? 1);
$note = $_POST['note'] ?? '';

//find product price from products.json
foreach ($products as $p) {
    if ($p['name'] === $productName) {
        $price = floatval($p['price']);
        break;
    }
}

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

if ($_SERVER["REQUEST_METHOD"] === "POST" && $productName !== '') {
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

    if ($action === 'add') {
        // Add or update session cart
        if (isset($_SESSION['cart'][$productName])) {
            $_SESSION['cart'][$productName]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$productName] = [
                'name' => $productName,
                'price' => $price,
                'quantity' => $quantity,
                'note' => $note
            ];
        }

        // Insert or update in DB
        $stmt = $conn->prepare("SELECT id FROM cart_items WHERE user_id = ? AND product_name = ?");
        $stmt->bind_param("is", $user_id, $productName);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $stmt = $conn->prepare("UPDATE cart_items SET quantity = quantity + ?, note_to_seller = ? WHERE user_id = ? AND product_name = ?");
            $stmt->bind_param("isis", $quantity, $note, $user_id, $productName);
        } else {
            $stmt = $conn->prepare("INSERT INTO cart_items (user_id, product_name, price, quantity, note_to_seller) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("isdis", $user_id, $productName, $price, $quantity, $note);
        }
        $stmt->execute();
        header("Location: addtocart.php");
        exit;
    }

    if ($action === 'update') {
      if (isset($_SESSION['cart'][$productName])) {
          $_SESSION['cart'][$productName]['quantity'] = $quantity;
          $_SESSION['cart'][$productName]['note'] = $note;

          //preserve name if missing
          if (!isset($_SESSION['cart'][$productName]['name'])) {
              $_SESSION['cart'][$productName]['name'] = $productName;
          }
      }

      $stmt = $conn->prepare("UPDATE cart_items SET quantity = ?, note_to_seller = ? WHERE user_id = ? AND product_name = ?");
      $stmt->bind_param("isis", $quantity, $note, $user_id, $productName);
      $stmt->execute();

      header("Location: addtocart.php");
      exit;
    }

    if (isset($_POST['remove_item'])) {
        unset($_SESSION['cart'][$productName]);
        $stmt = $conn->prepare("DELETE FROM cart_items WHERE user_id = ? AND product_name = ?");
        $stmt->bind_param("is", $user_id, $productName);
        $stmt->execute();
        header("Location: addtocart.php");
        exit;
    }
}

// Calculate subtotal
$subtotal = 0;
foreach ($_SESSION['cart'] as $item) {
    if (!is_array($item) || empty($item)) continue;
    $subtotal += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang = "en">
<head>
  <meta charset="UTF-8">
  <title>Shopping Cart</title>
  <link rel = "stylesheet" href = "ls-userdashboard.css">
</head>

<body>
<header class = "navbar">
    <div class = "nav-left">
        <img src = "kantanshoplogo.png" alt="Logo" class="logo">
    </div>
    <div class = "nav-right">
        <a href = "userhomepage.php">Home</a>
        <a href = "usercatalog.php">Catalog</a>
    </div>
</header>

<div class = "cart-container">
  <div class = "cart-items">
    <h2>Your Cart</h2>
    <?php foreach ($_SESSION['cart'] as $name => $item): ?>
    <?php if (!is_array($item) || empty($item)) continue; ?>
    <div class = "cart-item">
      <strong><?= htmlspecialchars($name) ?></strong><br>
      <p>Price: ₱<?= number_format($item['price'], 2) ?></p>

      <form method = "POST">
        <input type = "hidden" name = "action" value = "update">
        <input type = "hidden" name = "product" value = "<?= htmlspecialchars($name) ?>">
        <input type = "number" name = "quantity" value = "<?= $item['quantity'] ?>" min="1" required>

        <div class = "note-to-seller">
          <label for = "note">Note to Seller:</label>
          <textarea name = "note" placeholder = "Add a note..."><?= htmlspecialchars($item['note'] ?? '') ?></textarea>
        </div>

        <button type = "submit">Update</button>
      </form>

      <form method = "POST">
        <input type = "hidden" name = "remove_item" value = "1">
        <input type = "hidden" name = "product" value = "<?= htmlspecialchars($name) ?>">
        <button type = "submit">Remove</button>
      </form>

      <p><strong>Total: ₱<?= number_format($item['price'] * $item['quantity'], 2) ?></strong></p>
    </div>
    <?php endforeach; ?>
  </div>

  <div class = "cart-summary">
    <h2>Summary</h2>
    <p>Items: <?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?></p>
    <p>Subtotal: ₱<?= number_format($subtotal, 2) ?></p>
    <p>Total: ₱<?= number_format($subtotal, 2) ?></p>
    <form action = "checkout.php" method = "POST">
        <button type = "submit" name = "checkout">Checkout</button>
    </form>
  </div>
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