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

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'require_login' => true, 'message' => 'Please login to save items to your wishlist']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$productId = (int)($input['product_id'] ?? ($_POST['product_id'] ?? 0));
$userId = $_SESSION['user_id'];

if ($productId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
    exit();
}

$product = getProduct($productId);
if (!$product) {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit();
}

// Toggle wishlist
$isAlready = isInWishlist($userId, $productId);
if ($isAlready) {
    removeFromWishlist($userId, $productId);
    echo json_encode([
        'success' => true,
        'action' => 'removed',
        'message' => 'Removed "' . htmlspecialchars($product['name']) . '" from your wishlist.',
        'wishlist_count' => getWishlistCount($userId),
        'in_wishlist' => false
    ]);
} else {
    addToWishlist($userId, $productId);
    echo json_encode([
        'success' => true,
        'action' => 'added',
        'message' => 'Saved "' . htmlspecialchars($product['name']) . '" to your wishlist!',
        'wishlist_count' => getWishlistCount($userId),
        'in_wishlist' => true
    ]);
}
?>
