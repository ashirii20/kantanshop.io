<?php
session_start();

$wishlist = $_SESSION['wishlist'] ?? [];

// Load products from JSON file
$products = json_decode(file_get_contents('products.json'), true);

// Get product name from POST
$productName = $_POST['product'] ?? '';

//find product
$product = null;
foreach ($products as $p) {
    if ($p['name'] === $productName) {
        $product = $p;
        break;
    }
}

//initialize wishlist if not set
if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

//add item to wishlist
if (isset($_POST['add'])) {
    $item = $_POST['add'];
    if (!in_array($item, $_SESSION['wishlist'])) {
        $_SESSION['wishlist'][] = $item;
    }
    header('Location: wishlist.php');
    exit;
}

//remove item from wishlist
if (isset($_POST['remove'])) {
    $item = $_POST['remove'];
    $_SESSION['wishlist'] = array_filter($_SESSION['wishlist'], function($i) use ($item) {
        return $i !== $item;
    });
    header('Location:  wishlist.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang = "en">
<head>
    <meta charset = "UTF-8">
    <title>Wishlist</title>
    <link rel = "stylesheet" href = "ls-userdashboard.css">
</head>
<body>
<header class = "navbar">
    <div class = "nav-left">
        <img src = "kantanshoplogo.png" alt = "Logo" class = "logo">
    </div>
    <div class = "nav-right">
        <a href = "userhomepage.php">Home</a>
        <a href = "usercatalog.php">Catalog</a>
        <a href = "userprofile.php" class = "user-avatar-link">User</a>
    </div>
</header>

<main class = "main-content" style = "padding: 25px;">
    <h2>Your Wishlist</h2>
    <div class = "product-grid">
        <?php foreach ($products as $product): ?>
            <?php if (in_array($product['name'], $wishlist)): ?>
                <div class = "product-card" style = "background-color: #d9d6d6;">
                    <img src = "<?= $product['image'] ?>" alt = "<?= $product['name'] ?>" class = "product-img">
                    <div class = "product-info">
                        <strong>
                        <a href="productdetail.php?product=<?= urlencode($product['name']) ?>">
                            <?= htmlspecialchars($product['name']) ?>
                        </a>
                        </strong>
                        <p><?= htmlspecialchars($product['description']) ?></p>
                        <p><strong>₱<?= number_format($product['price'], 2) ?></strong></p>
                    </div>
                    <div class = "product-actions">
                        <form action = "wishlist.php" method = "POST" style = "display:inline;">
                            <input type = "hidden" name = "remove" value = "<?= htmlspecialchars($product['name']) ?>">
                            <button type = "submit" class = "icon-btn">
                                <img src = "remove.png" class = "btn-icon" alt = "Remove">
                            </button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</main>

  <div class = "main-footer">
      <footer>
        <div class = "footer-content">
          <p>&copy; 2025 Kantan Shop. All rights reserved.</p>
        </div>
      </footer>
    </div>
</body>
</html>
