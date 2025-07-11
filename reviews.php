<?php
session_start();

$productName = $_GET['product'] ?? $_POST['product'] ?? '';
$ratingFilter = $_GET['rating'] ?? '';

//load reviews
$reviews = json_decode(file_get_contents('reviews.json'), true);

//filter reviews for this product
$productReviews = array_filter($reviews, function ($review) use ($productName, $ratingFilter) {
    $matchProduct = $review['product'] === $productName;
    $matchRating = $ratingFilter === '' || $review['rating'] == $ratingFilter;
    return $matchProduct && $matchRating;
});

//handle new review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review'])) {
    $newReview = [
        'product' => $productName,
        'user' => $_POST['user'] ?? 'Anonymous',
        'rating' => intval($_POST['rating']),
        'review' => $_POST['review']
    ];

    $reviews[] = $newReview;
    file_put_contents('reviews.json', json_encode($reviews, JSON_PRETTY_PRINT));
    header("Location: reviews.php?product=" . urlencode($productName));
    exit;
}
?>

<!DOCTYPE html>
<html lang = "en">
<head>
  <meta charset = "UTF-8">
  <title>Reviews for <?= htmlspecialchars($productName) ?></title>
  <link rel = "stylesheet" href = "ls-userdashboard.css">
</head>
<body>

<header class = "navbar">
  <div class = "nav-left">
    <img src = "kantanshoplogo.png" alt = "Logo" class = "logo">
  </div>
  <div class = "nav-right">
    <a href="productdetail.php?product=<?= urlencode($productName) ?>">Back to Product</a>
  </div>
</header>

<main style = "padding: 20px;">
  <h1>Reviews for <?= htmlspecialchars($productName) ?></h1>

  <?php if (count($productReviews) > 0): ?>
    <?php foreach ($productReviews as $review): ?>
      <div style = "border-bottom: 1px solid #ccc; margin-bottom: 10px;">
        <strong><?= htmlspecialchars($review['user']) ?></strong> - 
        <span><?= str_repeat('⭐', $review['rating']) ?></span>
        <p><?= htmlspecialchars($review['review']) ?></p>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p>No reviews found for this rating.</p>
  <?php endif; ?>

  <h2>Write a Review</h2>
  <form method = "POST" action = "reviews.php?product=<?= urlencode($productName) ?>">
    <input type = "hidden" name = "product" value = "<?= htmlspecialchars($productName) ?>">
    <label>Name: <input type = "text" name = "user"></label><br><br>
    <label>Rating:
      <select name = "rating">
        <option value = "1">1 ⭐</option>
        <option value = "2">2 ⭐</option>
        <option value = "3">3 ⭐</option>
        <option value = "4">4 ⭐</option>
        <option value = "5">5 ⭐</option>
      </select>
    </label><br><br>
    <label>Review:<br>
      <textarea name = "review" rows = "4" cols = "50"></textarea>
    </label><br><br>
    <button class = "reviews-button" type = "submit">Submit Review</button>
  </form>
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