<?php
session_start();

$productListing = $_GET['productlisting'] ?? '';

$productsPerPage = 5; //show 5 products per page
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$startIndex = ($page - 1) * $productsPerPage;

//load products
$products = json_decode(file_get_contents('products.json'), true);

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
  <link rel = "stylesheet" href = "lsdashboard.css">
</head>

<body>
  <!-- navigation bar -->
  <header>
    <div class = "navbar">
      <div class = "nav-left">
        <img src = "kantanshoplogo.png" alt = "logo" class = "logo">
      </div>
      <div class = "nav-right">
        <a href = "home.html">Home</a>
        <a href = "about.html">About</a>
        <a href = "contact.html">Contact</a>
        <a href = "loginsignup.html">Login</a>
      </div>
    </div>
  </header>

  <!-- layout -->
  <div class = "main-content" style = "display: flex; flex-direction: row; padding: 25px;">

    <!-- sidebar -->
    <aside style = "width: 200px; margin-right: 30px;">
      <h3>Catalog</h3>
      <ul>
        <li><a href = "productcatalog.php">All</a></li>
        <li><a href = "productcatalog.php?category=Category 1">Vtuber Merchandise</a></li>
        <li><a href = "productcatalog.php?category=Category 2">Anime Merchandise</a></li>
        <li><a href = "productcatalog.php?category=Category 3">Japanese Food</a></li>
        <li><a href = "productcatalog.php?category=Category 4">Japanese Clothes</a></li>
        <li><a href = "productcatalog.php?category=Category 5">Japanese Merchandise</a></li>
      </ul>
      <h3 style="margin-top: 30px;">Product Listing</h3>
      <ul>
        <li><a href = "productcatalog.php">All</a></li>
        <li><a href = "productcatalog.php?productlisting=Hatsune Miku Figurine">Hatsune Miku Figurine</a></li>
        <li><a href = "productcatalog.php?productlisting=Nanashi Mumei Plushie">Nanashi Mumei Plushie</a></li>
        <li><a href = "productcatalog.php?productlisting=Banana Fish Manga">Banana Fish Manga</a></li>
        <li><a href = "productcatalog.php?productlisting=My Hero Academia Manga">My Hero Academia Manga</a></li>
        <li><a href = "productcatalog.php?productlisting=Nijisanji EN Luxiem Merchandise">Nijisanji EN Luxiem Merchandise</a></li>
      </ul>
    </aside>

    <!-- main product area -->
    <section class = "product-area">
      <!-- search bar -->
      <form method = "GET" action = "productcatalog.php" class = "search-form">
      <input type = "text" name = "search" class = "search-input" placeholder = "Search products...">
      <button type = "submit" class = "search-button">🔍</button>
      </form>

      <!-- category cards -->
      <div class = "category-container">
        <a href =  "productcatalog.php?category=Category 1" class = "category-card">
          <img src = "vtubermerch.png" alt = "Vtuber Merchandise" class = "category-img">
          <p>Vtuber Merchandise</p>
        </a>

        <a href = "productcatalog.php?category=Category 2" class = "category-card">
          <img src = "animemerch.png" alt = "Anime Merchandise" class = "category-img">
          <p>Anime Merchandise</p>
        </a>

        <a href = "productcatalog.php?category=Category 3" class = "category-card">
          <img src = "jpnfood.jpg" alt = "Japanese Food" class = "category-img">
          <p>Japanese Food</p>
        </a>

        <a href = "productcatalog.php?category=Category 4" class = "category-card">
          <img src = "jpnclothes.png" alt = "Japanese Clothes" class = "category-img">
          <p>Japanese Clothes</p>
        </a>

        <a href = "productcatalog.php?category=Category 5" class = "category-card">
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
              <form action = "addtocart.php" method = "POST" style = "display: flex; gap: 10px; justify-content: center;">
                <input type = "hidden" name = "product" value = "<?= htmlspecialchars($product['name']) ?>">
                <button class = "icon-btn"><img src = "cart.png" class = "btn-icon" alt = "Cart"></button>
                <button class = "icon-btn"><img src = "heart.png" class = "btn-icon" alt = "Wishlist"></button>
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