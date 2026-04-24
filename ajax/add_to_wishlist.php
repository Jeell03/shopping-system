<?php
session_start();
include '../config/database.php';
include '../includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

// Check if user is logged in
if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Please login to add items to wishlist']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$productId = (int)$input['product_id'];
$userId = $_SESSION['user_id'];

if ($productId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
    exit();
}

// Check if product exists
$product = getProduct($productId);
if (!$product) {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit();
}

// Check if item is already in wishlist
$stmt = $pdo->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
$stmt->execute([$userId, $productId]);
if ($stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Item already in wishlist']);
    exit();
}

// Add to wishlist
try {
    $stmt = $pdo->prepare("INSERT INTO wishlist (user_id, product_id, created_at) VALUES (?, ?, NOW())");
    $stmt->execute([$userId, $productId]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Item added to wishlist'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error adding to wishlist'
    ]);
}
?>




