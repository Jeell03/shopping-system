<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');

include '../config/database.php';
include '../includes/functions.php';

clearCart();

echo json_encode([
    'success' => true,
    'message' => 'Cart cleared successfully',
    'cart_count' => 0
]);
