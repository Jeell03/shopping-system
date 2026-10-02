<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');

include '../config/database.php';
include '../includes/functions.php';

unset($_SESSION['applied_coupon']);
$newBreakdown = getCartBreakdown();

echo json_encode([
    'success' => true,
    'message' => 'Coupon removed.',
    'breakdown' => $newBreakdown
]);
?>
