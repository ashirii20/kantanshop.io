<?php
session_start();

//load products
$products = json_decode(file_get_contents('products.json'), true);

$productListing = $_GET['productlisting'] ?? '';

$productsPerPage = 8; //show 8 products per page (will only show 2 in one page)
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$startIndex = ($page - 1) * $productsPerPage;

//apply filters
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

$filteredProducts = array_filter($products, function ($product) use ($search, $category, $productListing) {
  $matchSearch = $search === '' || stripos($product['name'], $search) !== false;
  $matchCategory = $category === '' || $product['category'] === $category;
  $matchListing = $productListing === '' || stripos($product['name'], $productListing) !== false;
  return $matchSearch && $matchCategory && $matchListing;
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
  <title>User Home Page</title>
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
    <a href = "userprofile.php" class = "user-avatar-link">User</a>
  </div>
</header>


  <!-- layout -->
  <div class = "main-content" style = "display: flex; flex-direction: row; padding: 25px;">

    <!-- sidebar -->
    <aside class = "sidebar">
      <div class = "sidebar-section">
      <h3>Categories</h3>
      <ul>
        <li><a href = "userhomepage.php">All</a></li>
        <li><a href = "userhomepage.php?category=Category 1">Vtuber Merchandise</a></li>
        <li><a href = "userhomepage.php?category=Category 2">Anime Merchandise</a></li>
        <li><a href = "userhomepage.php?category=Category 3">Japanese Food</a></li>
        <li><a href = "userhomepage.php?category=Category 4">Japanese Clothes</a></li>
        <li><a href = "userhomepage.php?category=Category 5">Japanese Merchandise</a></li>
      </ul>
      <h3>Product Listing</h3>
      <ul>
        <li><a href = "userhomepage.php">All</a></li>
        <li><a href = "userhomepage.php?productlisting=Hatsune Miku Figurine">Hatsune Miku Figurine</a></li>
        <li><a href = "userhomepage.php?productlisting=Nanashi Mumei Plushie">Nanashi Mumei Plushie</a></li>
        <li><a href = "userhomepage.php?productlisting=Banana Fish Manga">Banana Fish Manga</a></li>
        <li><a href = "userhomepage.php?productlisting=My Hero Academia Manga">My Hero Academia Manga</a></li>
        <li><a href = "userhomepage.php?productlisting=Nijisanji EN Luxiem Merchandise">Nijisanji EN Luxiem Merchandise</a></li>
      </ul>
      </div>
    </aside>

    <!-- main product area -->
    <section class = "product-area">
      <!-- search bar -->
      <div class = "search-filter-bar">
        <div class = "search-wrapper">
          <form method = "GET" action = "usercatalog.php" class = "search-form">
          <input type = "text" name = "search" class = "search-input" placeholder = "Search products...">
          <button type = "submit" class = "search-button">
            <img src = "searchicon.png" alt = "Search" class = "search-icon">
          </button>
          </form>
        </div>
      </div>

      <!-- category cards -->
      <h2 class = "section-title">Categories</h2>
      <div class = "category-container">
        <a href =  "userhomepage.php?category=Category 1" class = "category-card">
          <img src = "vtubermerch.png" alt = "Vtuber Merchandise" class = "category-img">
          <p>Vtuber Merchandise</p>
        </a>

        <a href = "userhomepage.php?category=Category 2" class = "category-card">
          <img src = "animemerch.png" alt = "Anime Merchandise" class = "category-img">
          <p>Anime Merchandise</p>
        </a>

        <a href = "userhomepage.php?category=Category 3" class = "category-card">
          <img src = "jpnfood.jpg" alt = "Japanese Food" class = "category-img">
          <p>Japanese Food</p>
        </a>

        <a href = "userhomepage.php?category=Category 4" class = "category-card">
          <img src = "jpnclothes.png" alt = "Japanese Clothes" class = "category-img">
          <p>Japanese Clothes</p>
        </a>

        <a href = "userhomepage.php?category=Category 5" class = "category-card">
          <img src = "jpnmerch1.png" alt = "Japanese Merchandise" class = "category-img">
          <p>Japanese Merchandise</p>
        </a>
      </div>

      <!-- product cards -->
      <h2 class = "section-title">Recommendations</h2>
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
</html>