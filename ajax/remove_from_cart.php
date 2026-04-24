<?php
session_start();
include '../config/database.php';
include '../includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$productId = (int)$input['product_id'];

if ($productId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
    exit();
}

removeFromCart($productId);

echo json_encode([
    'success' => true,
    'message' => 'Product removed from cart',
    'cart_count' => getCartCount()
]);
?>




