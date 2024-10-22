<?php
require_once '../models/Product.php';

if (isset($_GET['query']) && !empty(trim($_GET['query']))) {
    $query = trim($_GET['query']);
    $product = new Product();
    $products = $product->searchByNameOrDescription($query);
    require_once '../views/product/search.php';
} else {
    header("Location: ../../product/index");
    exit();
}
