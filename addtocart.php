<?php
session_start();

$product = $_POST['product'] ?? '';
if ($product !== '') {
    $_SESSION['cart'][] = $product;
}

header('Location: productcatalog.php');
exit;