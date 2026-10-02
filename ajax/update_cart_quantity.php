<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');

include '../config/database.php';
include '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$productId = (int)($input['product_id'] ?? ($_POST['product_id'] ?? 0));
$quantity = (int)($input['quantity'] ?? ($_POST['quantity'] ?? 0));

if ($productId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
    exit();
}

if ($quantity > 0) {
    $product = getProduct($productId);
    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit();
    }
    if ($product['stock_quantity'] < $quantity) {
        echo json_encode([
            'success' => false, 
            'message' => 'Cannot exceed available stock of ' . $product['stock_quantity'] . ' units.',
            'max_stock' => (int)$product['stock_quantity']
        ]);
        exit();
    }
}

updateCartQuantity($productId, $quantity);
$breakdown = getCartBreakdown();

echo json_encode([
    'success' => true,
    'message' => $quantity > 0 ? 'Cart updated successfully' : 'Item removed from cart',
    'cart_count' => getCartCount(),
    'breakdown' => $breakdown
]);
?>
