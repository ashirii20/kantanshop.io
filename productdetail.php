<?php
session_start();

//load products
$products = json_decode(file_get_contents('products.json'), true);

//get product name from POST and GET
$productName = $_POST['product'] ?? $_GET['product'] ?? '';
;

//find product
$product = null;
foreach ($products as $p) {
    if ($p['name'] === $productName) {
        $product = $p;
        break;
    }
}

if (!$product) {
    echo "<h1>Product not found.</h1>";
    exit;
}


//find similar items by category (excluding the current product)
$similarItems = array_filter($products, function ($p) use ($product) {
  return $p['category'] === $product['category'] && $p['name'] !== $product['name'];
});

//limit to 10 items
$similarItems = array_slice($similarItems, 0, 10);
?>

<!DOCTYPE html>
<html lang = "en">
<head>
  <meta charset = "UTF-8">
  <title><?= htmlspecialchars($product['name']) ?> - Product Detail</title>
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
  </div>
</header>

<main class = "product-detail-container">
  <div class = "product-image">
    <img src = "<?= htmlspecialchars($product['image']) ?>" alt = "<?= htmlspecialchars($product['name']) ?>">
    <form action = "reviews.php" method = "GET">
      <input type = "hidden" name = "product" value="<?= htmlspecialchars($product['name']) ?>">
      <button type = "submit" class = "reviews-button">Reviews</button>
    </form>
  </div>

  <div class = "product-info">
    <h1><?= htmlspecialchars($product['name']) ?></h1>
    <p><?= htmlspecialchars($product['description']) ?></p>
    <p class = "price">₱<?= number_format($product['price'], 2) ?></p>

  <div class = "quantity-selector">
    <button onclick = "changeQuantity(-1)">−</button>
    <input type = "number" id = "quantity" value = "1" min="1">
    <button onclick = "changeQuantity(1)">+</button>
  </div>

  <div class = "shipping-info">
    <h3>Shipping Information</h3>
    <p>Ships within 3-5 business days.</p>
  </div>

  <div class = "payment-info">
    <h3>Payment Information</h3>
    <p>Secure payment via Cash on Delivery or Online Payment.</p>
  </div>

  <form action = "addtocart.php" method = "POST">
    <input type = "hidden" name = "action" value = "add">
    <input type = "hidden" name = "product" value="<?= htmlspecialchars($product['name']) ?>">
    <input type = "hidden" name = "price" value="<?= $product['price'] ?>">
    <input type = "hidden" name = "quantity" id = "formQuantity" value="1">
    <button type = "submit" class = "buy-button">Buy Now</button>
  </form>
 </div>
</main>

  <section class = "similar-items">
    <h2>Similar Items</h2>
    <div class = "product-grid">
        <?php foreach ($similarItems as $product): ?>
          <div class = "product-card">
            <img src = "<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-img">
            <div class = "product-info">
              <strong><?= htmlspecialchars($product['name']) ?></strong>
              <p><?= htmlspecialchars($product['description']) ?></p>
              <p><strong>₱<?= number_format($product['price'], 2) ?></strong></p>
            </div>

            <div class = "product-actions">
                <form action = "productdetail.php" method = "POST" style = "display:inline;">
                  <input type = "hidden" name = "product" value = "<?= htmlspecialchars($product['name']) ?>">
                  <button type = "submit" class = "icon-btn">
                    <img src = "cart.png" class = "btn-icon" alt = "Cart">
                  </button>
                </form>

                <form action = "productdetail.php" method = "POST" style = "display:inline;">
                  <input type = "hidden" name = "product" value = "<?= htmlspecialchars($product['name']) ?>">
                  <button type = "submit" class = "icon-btn">
                    <img src = "heart.png" class = "btn-icon" alt = "Wishlist">
                  </button>
                </form>
              </div>
          </div>
        <?php endforeach; ?>
    </div>
  </section>

  <script>
    function changeQuantity(amount) {
    const input = document.getElementById('quantity');
    let value = parseInt(input.value) + amount;
    if (value < 1) value = 1;
    input.value = value;
  document.getElementById('formQuantity').value = value;
}
  </script>
</body>
</html>