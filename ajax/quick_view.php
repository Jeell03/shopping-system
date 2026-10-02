<?php
header('Content-Type: application/json');

include '../config/database.php';
include '../includes/functions.php';

$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($productId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
    exit();
}

$product = getProduct($productId);
if (!$product) {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit();
}

$effectivePrice = ($product['sale_price'] && $product['sale_price'] > 0) ? (float)$product['sale_price'] : (float)$product['price'];
$discountPct = ($product['sale_price'] && $product['sale_price'] > 0) ? round((($product['price'] - $product['sale_price']) / $product['price']) * 100) : 0;

echo json_encode([
    'success' => true,
    'product' => [
        'id' => $product['id'],
        'name' => $product['name'],
        'category_name' => $product['category_name'] ?? 'General',
        'category_slug' => $product['category_slug'] ?? '',
        'price' => number_format($product['price'], 2),
        'sale_price' => $product['sale_price'] ? number_format($product['sale_price'], 2) : null,
        'effective_price' => number_format($effectivePrice, 2),
        'discount_pct' => $discountPct,
        'image' => getProductImageUrl($product['image']),
        'short_description' => $product['short_description'] ?: substr(strip_tags($product['description']), 0, 180) . '...',
        'stock_quantity' => (int)$product['stock_quantity'],
        'in_stock' => $product['stock_quantity'] > 0,
        'sku' => $product['sku'],
        'rating' => round($product['avg_rating'], 1),
        'reviews_count' => (int)$product['review_count'],
        'url' => 'product.php?id=' . $product['id']
    ]
]);
?>
