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
$quantity = (int)($input['quantity'] ?? ($_POST['quantity'] ?? 1));
$quantity = max(1, $quantity);

if ($productId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
    exit();
}

$product = getProduct($productId);
if (!$product) {
    echo json_encode(['success' => false, 'message' => 'Product not found or unavailable']);
    exit();
}

$currentInCart = (isset($_SESSION['cart'][$productId])) ? (int)$_SESSION['cart'][$productId] : 0;
if (($currentInCart + $quantity) > $product['stock_quantity']) {
    $remaining = max(0, $product['stock_quantity'] - $currentInCart);
    $msg = ($remaining > 0) 
        ? "You already have $currentInCart in your cart. Only $remaining more unit" . ($remaining > 1 ? 's' : '') . " can be added."
        : "You already have the maximum available stock ($currentInCart units) in your cart.";
    echo json_encode(['success' => false, 'message' => $msg]);
    exit();
}

addToCart($productId, $quantity);
$breakdown = getCartBreakdown();

echo json_encode([
    'success' => true,
    'message' => 'Added "' . htmlspecialchars($product['name']) . '" to your cart!',
    'cart_count' => getCartCount(),
    'product' => [
        'id' => $product['id'],
        'name' => $product['name'],
        'price' => number_format(($product['sale_price'] && $product['sale_price'] > 0) ? $product['sale_price'] : $product['price'], 2)
    ],
    'breakdown' => $breakdown
]);
?>
