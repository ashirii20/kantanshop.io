<?php
session_start();

$productListing = $_GET['productlisting'] ?? '';

$productsPerPage = 8; //show 8 products per page (will only show 2 in one page)
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$startIndex = ($page - 1) * $productsPerPage;

//load products
$products = json_decode(file_get_contents('products.json'), true);

//apply filters
$search = $_GET['search'] ?? '';
$price = $_GET['price'] ?? '';
$rating = $_GET['rating'] ?? '';

$filteredProducts = array_filter($products, function ($product) use ($search, $price, $rating, $productListing) {
  //for search
  $matchSearch = $search === '' || stripos($product['name'], $search) !== false;

  //for price
  $matchPrice = true;
    if ($price !== '') {
        if (strpos($price, '-') !== false) {
            list($min, $max) = explode('-', $price);
            $min = (float)$min;
            $max = (float)$max;
            $matchPrice = $product['price'] >= $min && $product['price'] <= $max;
        }
    }

  //for rating
  $matchRating = $rating === '' || $product['rating'] == $rating;
  return $matchSearch && $matchPrice && $matchRating;
});

//paginate functionality
$totalProducts = count($filteredProducts);
$paginatedProducts = array_slice(array_values($filteredProducts),
                                  $startIndex, $productsPerPage);
?>

<!DOCTYPE html>
<html lang = "en">

<head>
  <meta charset = "UTF-8">
  <title>Product Catalog</title>
  <link rel = "stylesheet" href = "ls-userdashboard.css">
</head>

<body>
  <!-- navigation bar -->
  <header class = "navbar">
    <div class = "nav-left">
      <img src = "kantanshoplogo.png" alt = "Logo" class = "logo">
    </div>
    <div class = "nav-right">
      <a href = "userhomepage.php">Home</a>
    </div>
    <div class = "user-avatar"></div>
  </header>

  <!-- main content -->
  <div class = "main-content">
    <!-- sidebar -->
    <aside class = "sidebar" style = "margin-top: 20px; margin-bottom: 20px; margin-left: 20px;">
      <div class = "sidebar-section">
        <h3>Filter by Price</h3>
        <ul>
        <li><a href = "usercatalog.php">All</a></li>
        <li><a href = "usercatalog.php?price=0-50">Under ₱50</a></li>
        <li><a href = "usercatalog.php?price=50-100">₱50 - ₱100</a></li>
        <li><a href = "usercatalog.php?price=100-200">₱100 - ₱200</a></li>
        <li><a href = "usercatalog.php?price=200-500">₱200 - ₱500</a></li>
        <li><a href = "usercatalog.php?price=500-25000">Above ₱500</a></li>
        </ul>
        <h3>Filter by Rating</h3>
        <ul>
        <li><a href = "usercatalog.php">All</a></li>
        <li><a href = "usercatalog.php?rating=1">1 Star</a></li>
        <li><a href = "usercatalog.php?rating=2">2 Stars</a></li>
        <li><a href = "usercatalog.php?rating=3">3 Stars</a></li>
        <li><a href = "usercatalog.php?rating=4">4 Stars</a></li>
        <li><a href = "usercatalog.php?rating=5">5 Stars</a></li>
        </ul>
      </div>
    </aside>

    <!-- main product area -->
    <section class = "product-area">
        <!-- search bar -->
        <div class = "search-filter-bar">
            <form class = "search-form" method = "GET" action = "usercatalog.php">
            <input type = "text" name = "search" class = "search-input" placeholder = "Search products...">
            <button type = "submit" class = "search-btn" style = "border: 1px solid #fefefe;background-color: #fefefe;">
                <img src = "searchicon.png" alt = "Search" class = "search-icon">
            </button>
            </form>
        </div>

      <!-- product cards -->
      <div class = "product-grid">
        <?php foreach ($paginatedProducts as $product): ?>
          <div class = "product-card">
            <img src = "<?= htmlspecialchars($product['image']) ?>" alt = "<?= htmlspecialchars($product['name']) ?>" class = "product-img">

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

        <div class = "pagination" style = "margin-top: 20px; text-align: center;">
          <?php if ($page > 1): ?>
            <a href = "?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>&category=<?= urlencode($category) ?>" class = "pagination-btn">❮</a>
          <?php endif; ?>
          <?php if ($startIndex + $productsPerPage < $totalProducts): ?>
            <a href = "?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>&category=<?= urlencode($category) ?>" class = "pagination-btn">❯</a>
          <?php endif; ?>
        </div>

    </section>
  </div>
</body>
