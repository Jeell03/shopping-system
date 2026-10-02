<?php
header('Content-Type: application/json');

include '../config/database.php';
include '../includes/functions.php';

$query = trim($_GET['q'] ?? '');
$category = trim($_GET['category'] ?? '');

if (strlen($query) < 2) {
    echo json_encode(['results' => []]);
    exit();
}

$params = [
    'search' => $query,
    'limit'  => 6,
    'sort'   => 'featured'
];

if (!empty($category)) {
    $params['category'] = $category;
}

$products = getFilteredProductsAdvanced($params);

$formatted = [];
foreach ($products as $p) {
    $price = ($p['sale_price'] && $p['sale_price'] > 0) ? $p['sale_price'] : $p['price'];
    $formatted[] = [
        'id' => $p['id'],
        'name' => $p['name'],
        'category' => $p['category_name'] ?? 'Product',
        'price' => '$' . number_format($price, 2),
        'original_price' => ($p['sale_price'] && $p['sale_price'] > 0) ? '$' . number_format($p['price'], 2) : null,
        'image' => getProductImageUrl($p['image']),
        'url' => 'product.php?id=' . $p['id'],
        'rating' => round($p['avg_rating'], 1),
        'in_stock' => $p['stock_quantity'] > 0
    ];
}

echo json_encode(['results' => $formatted]);
?>
