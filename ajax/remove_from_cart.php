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

if ($productId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
    exit();
}

removeFromCart($productId);
$breakdown = getCartBreakdown();

echo json_encode([
    'success' => true,
    'message' => 'Item removed from cart',
    'cart_count' => getCartCount(),
    'breakdown' => $breakdown
]);
?>
