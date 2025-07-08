<?php
session_start();

//load products
$products = json_decode(file_get_contents('products.json'), true);

$productName = $_POST['product'] ?? '';
$product = null;

foreach ($products as $p) {
    if ($p['name'] === $productName) {
        $product = $p;
        break;
    }
}

//handle add to cart
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'] ?? '';
    $productName = $_POST['product'] ?? '';
    $price = floatval($_POST['price'] ?? 0);
    $quantity = intval($_POST['quantity'] ?? 1);

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if ($action === 'add' && $productName !== '') {
        if (isset($_SESSION['cart'][$productName])) {
            $_SESSION['cart'][$productName]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$productName] = [
                'price' => $price,
                'quantity' => $quantity
            ];
        }
        header("Location: addtocart.php");
        exit;
    }

    if ($action === 'update' && $productName !== '') {
        if (isset($_SESSION['cart'][$productName])) {
            $_SESSION['cart'][$productName]['quantity'] = $quantity;
        }
        header("Location: addtocart.php");
        exit;
    }

    if (isset($_POST['remove_item']) && $productName !== '') {
        unset($_SESSION['cart'][$productName]);
        header("Location: addtocart.php");
        exit;
    }
}

//find similar items
$similarItems = [];

if ($product) {
    $similarItems = array_filter($products, function ($p) use ($product) {
        return $p['category'] === $product['category'] && $p['name'] !== $product['name'];
    });

    $similarItems = array_slice($similarItems, 0, 10);
}

//calculate subtotal
$subtotal = 0;
foreach ($_SESSION['cart'] as $item) {
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
      <div class = "cart-item">
        <strong><?= htmlspecialchars($name) ?></strong><br>
        <p>Price: ₱<?= number_format($item['price'], 2) ?></p>
        <form method = "POST">
          <input type = "hidden" name = "action" value = "update">
          <input type = "hidden" name = "product" value = "<?= htmlspecialchars($name) ?>">
          <input type = "number" name = "quantity" value = "<?= $item['quantity'] ?>" min = "1">
          <button type = "submit">Update</button>
        </form>

        <form method = "POST">
          <input type = "hidden" name = "remove_item" value = "1">
          <input type = "hidden" name = "product" value = "<?= htmlspecialchars($name) ?>">
          <button type = "submit">Remove</button>
        </form>
        <div class = "note-to-seller">
          <label for = "note">Note to Seller:</label>
          <textarea name = "note" placeholder = "Add a note..."></textarea>
        </div>
        <p><strong>Total: ₱<?= number_format($item['price'] * $item['quantity'], 2) ?></strong></p>
      </div>
    <?php endforeach; ?>
  </div>

  <div class = "cart-summary">
    <h2>Summary</h2>
    <p>Items: <?= count($_SESSION['cart']) ?></p>
    <p>Subtotal: ₱<?= number_format($subtotal, 2) ?></p>
    <p>Total: ₱<?= number_format($subtotal, 2) ?></p>
    <form action = "checkout.php" method = "POST">
        <button type = "submit" name = "checkout">Checkout</button>
    </form>
  </div>
</div>
    </div>
</section>
</body>
</html>
