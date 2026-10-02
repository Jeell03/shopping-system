<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$orderId = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
if ($orderId <= 0) {
    header('Location: index.php');
    exit();
}

$userId = isLoggedIn() ? $_SESSION['user_id'] : null;
$order = getOrderDetails($orderId, $userId);

if (!$order) {
    header('Location: index.php');
    exit();
}

$pageTitle = 'Order Confirmed #' . htmlspecialchars($order['order_number']) . ' - ShopEasy';
$activeNav = 'profile';

require_once 'includes/header.php';
?>

<main class="order-confirmation-page" style="padding: 30px 0 60px;">
    <div class="container">
        <div class="order-success-card">
            <!-- Animated Green Checkmark -->
            <div class="checkmark-circle-success">
                <i class="fas fa-check"></i>
            </div>

            <h1 style="font-family: 'Poppins', sans-serif; font-size: 26px; font-weight: 800; color: #0f172a; margin-bottom: 6px;">
                Order Placed Successfully!
            </h1>
            <p style="font-size: 14px; color: #64748b; margin-bottom: 18px;">
                Confirmation sent to <strong><?php echo htmlspecialchars($order['email'] ?? 'your registered email'); ?></strong>. Your package is on its way.
            </p>

            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px 20px; display: inline-flex; gap: 24px; text-align: left; margin-bottom: 30px; flex-wrap: wrap;">
                <div>
                    <span style="font-size: 11px; color: #64748b; text-transform: uppercase; font-weight: 700;">Order Reference</span>
                    <div style="font-size: 14px; font-weight: 800; color: #0f172a;"><?php echo htmlspecialchars($order['order_number']); ?></div>
                </div>
                <div>
                    <span style="font-size: 11px; color: #64748b; text-transform: uppercase; font-weight: 700;">Estimated Delivery</span>
                    <div style="font-size: 14px; font-weight: 800; color: #10b981;">
                        <?php echo date('D, M j', strtotime($order['created_at'] . ' + 3 days')); ?>
                    </div>
                </div>
                <div>
                    <span style="font-size: 11px; color: #64748b; text-transform: uppercase; font-weight: 700;">Payment Status</span>
                    <div style="font-size: 14px; font-weight: 800; color: <?php echo $order['payment_status'] === 'paid' ? '#10b981' : '#f59e0b'; ?>;">
                        <?php echo strtoupper($order['payment_status']); ?> (<?php echo strtoupper($order['payment_method']); ?>)
                    </div>
                </div>
            </div>

            <!-- 4-Step Order Tracking Stepper (Flipkart / Amazon Pattern) -->
            <div class="order-tracking-stepper">
                <div class="tracking-step-node completed">
                    <div class="step-circle"><i class="fas fa-check"></i></div>
                    <span class="step-label">Order Placed</span>
                    <span style="font-size: 10px; color: #94a3b8;"><?php echo date('M j', strtotime($order['created_at'])); ?></span>
                </div>
                <div class="tracking-step-node active">
                    <div class="step-circle"><i class="fas fa-box"></i></div>
                    <span class="step-label">Processing</span>
                    <span style="font-size: 10px; color: #2563eb; font-weight: 700;">Today</span>
                </div>
                <div class="tracking-step-node">
                    <div class="step-circle"><i class="fas fa-shipping-fast"></i></div>
                    <span class="step-label">Shipped</span>
                    <span style="font-size: 10px; color: #94a3b8;">Upcoming</span>
                </div>
                <div class="tracking-step-node">
                    <div class="step-circle"><i class="fas fa-home"></i></div>
                    <span class="step-label">Delivered</span>
                    <span style="font-size: 10px; color: #94a3b8;">Est. +3 Days</span>
                </div>
            </div>

            <!-- Order Items Detail Table -->
            <div style="text-align: left; margin: 30px 0;">
                <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 14px; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px;">
                    Order Items Breakdown
                </h3>
                
                <div style="border: 1px solid #e2e8f0; border-radius: 10px; overflow: hidden; margin-bottom: 20px;">
                    <?php foreach ($order['items'] as $item): ?>
                    <?php $itemImg = getProductImageUrl($item['image']); ?>
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 14px 18px; border-bottom: 1px solid #f1f5f9;">
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <img src="<?php echo $itemImg; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" style="width: 50px; height: 50px; object-fit: contain; border-radius: 6px; background: #f8fafc; padding: 4px;">
                            <div>
                                <h4 style="font-size: 14px; font-weight: 600; color: #0f172a;"><?php echo htmlspecialchars($item['name']); ?></h4>
                                <span style="font-size: 12px; color: #64748b;">Qty: <?php echo $item['quantity']; ?> &bull; Unit Price: $<?php echo number_format($item['price'], 2); ?></span>
                            </div>
                        </div>
                        <div style="font-size: 15px; font-weight: 700; color: #0f172a;">
                            $<?php echo number_format($item['total'], 2); ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Dual Columns: Delivery Address + Payment Breakdown -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px;">
                        <h4 style="font-size: 13px; font-weight: 700; text-transform: uppercase; color: #0f172a; margin-bottom: 8px;">
                            <i class="fas fa-map-marker-alt" style="color: #2563eb;"></i> Shipping Address
                        </h4>
                        <p style="font-size: 13px; color: #475569; line-height: 1.6; white-space: pre-line;">
                            <?php echo htmlspecialchars($order['shipping_address']); ?>
                        </p>
                    </div>

                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px;">
                        <h4 style="font-size: 13px; font-weight: 700; text-transform: uppercase; color: #0f172a; margin-bottom: 8px;">
                            <i class="fas fa-receipt" style="color: #10b981;"></i> Total Paid
                        </h4>
                        <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 4px;">
                            <span>Subtotal:</span>
                            <span>$<?php echo number_format($order['subtotal'], 2); ?></span>
                        </div>
                        <?php if ($order['discount_amount'] > 0): ?>
                        <div style="display: flex; justify-content: space-between; font-size: 13px; color: #10b981; margin-bottom: 4px;">
                            <span>Discount:</span>
                            <span>-$<?php echo number_format($order['discount_amount'], 2); ?></span>
                        </div>
                        <?php endif; ?>
                        <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 4px;">
                            <span>Tax Amount:</span>
                            <span>$<?php echo number_format($order['tax_amount'], 2); ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 8px;">
                            <span>Shipping:</span>
                            <span><?php echo $order['shipping_amount'] == 0 ? '<strong style="color: #10b981;">FREE</strong>' : '$'.number_format($order['shipping_amount'], 2); ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 16px; font-weight: 800; border-top: 1px solid #cbd5e1; padding-top: 6px;">
                            <span>Grand Total:</span>
                            <span style="color: #2563eb;">$<?php echo number_format($order['total'], 2); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; gap: 14px; justify-content: center; margin-top: 24px;">
                <button type="button" class="btn btn-outline" onclick="window.print();">
                    <i class="fas fa-print"></i> Print Official Invoice
                </button>
                <?php if (isLoggedIn()): ?>
                    <a href="profile.php?tab=orders" class="btn btn-primary">
                        <i class="fas fa-box-open"></i> View in My Orders
                    </a>
                <?php endif; ?>
                <a href="index.php" class="btn btn-accent">
                    <i class="fas fa-shopping-bag"></i> Continue Shopping
                </a>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
