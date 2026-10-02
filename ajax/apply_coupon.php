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
$code = trim($input['coupon_code'] ?? ($_POST['coupon_code'] ?? ''));

if (empty($code)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a coupon code.']);
    exit();
}

$cartBreakdown = getCartBreakdown();
if (empty($cartBreakdown['items']) || $cartBreakdown['subtotal'] <= 0) {
    echo json_encode(['success' => false, 'message' => 'Your cart is empty. Add products to apply a coupon.']);
    exit();
}

$coupon = getCoupon($code);
if (!$coupon) {
    echo json_encode(['success' => false, 'message' => 'Invalid coupon code or offer has expired.']);
    exit();
}

if ($cartBreakdown['subtotal'] < (float)$coupon['minimum_amount']) {
    echo json_encode([
        'success' => false, 
        'message' => 'Minimum order of $' . number_format($coupon['minimum_amount'], 2) . ' is required for this coupon.'
    ]);
    exit();
}

$_SESSION['applied_coupon'] = $coupon['code'];
$newBreakdown = getCartBreakdown($coupon['code']);

echo json_encode([
    'success' => true,
    'message' => 'Coupon ' . htmlspecialchars($coupon['code']) . ' applied successfully!',
    'breakdown' => $newBreakdown
]);
?>
