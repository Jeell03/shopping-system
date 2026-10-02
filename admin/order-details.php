<?php
$orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($orderId <= 0) {
    header('Location: orders.php');
    exit();
}

$adminTitle = 'Order Details #' . $orderId;
$activeMenu = 'orders';

require_once 'header.php';

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_order_workflow'])) {
    $newStatus = $_POST['status'];
    $newPayment = $_POST['payment_status'];
    updateOrderStatus($orderId, $newStatus, $newPayment);
    $msg = 'Order updated successfully!';
}

$order = getOrderDetails($orderId);
if (!$order) {
    header('Location: orders.php');
    exit();
}
?>

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
    <div>
        <h1 style="font-family: 'Poppins', sans-serif; font-size: 24px; font-weight: 700; color: #0f172a;">
            Order #<?php echo htmlspecialchars($order['order_number']); ?>
        </h1>
        <p style="font-size: 13px; color: #64748b;">Placed on <?php echo date('F j, Y \a\t g:i A', strtotime($order['created_at'])); ?></p>
    </div>
    <div style="display: flex; gap: 10px;">
        <button type="button" class="btn btn-outline btn-sm" onclick="window.print();">
            <i class="fas fa-print"></i> Print Invoice
        </button>
        <a href="orders.php" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Orders
        </a>
    </div>
</div>

<?php if ($msg): ?>
    <div style="background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
        <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($msg); ?>
    </div>
<?php endif; ?>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: start;">
    <!-- Left Column: Items and Customer Information -->
    <div>
        <!-- Items Table -->
        <div class="admin-table-card">
            <div style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; font-weight: 700;">
                Ordered Products (<?php echo count($order['items']); ?> items)
            </div>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th style="text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order['items'] as $it): ?>
                    <?php $img = getProductImageUrl($it['image']); ?>
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <img src="../<?php echo $img; ?>" alt="" style="width: 40px; height: 40px; object-fit: contain; background: #f8fafc; border-radius: 4px; padding: 2px;">
                                <strong><?php echo htmlspecialchars($it['name']); ?></strong>
                            </div>
                        </td>
                        <td><?php echo htmlspecialchars($it['sku'] ?? 'N/A'); ?></td>
                        <td>$<?php echo number_format($it['price'], 2); ?></td>
                        <td><strong><?php echo $it['quantity']; ?></strong></td>
                        <td style="text-align: right;"><strong>$<?php echo number_format($it['total'], 2); ?></strong></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Delivery & Billing Address Card -->
        <div style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 20px; margin-bottom: 24px;">
            <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 12px;">
                <i class="fas fa-map-marker-alt" style="color: #2563eb;"></i> Shipping & Delivery Details
            </h3>
            <p style="font-size: 14px; color: #334155; line-height: 1.6; white-space: pre-line;">
                <?php echo htmlspecialchars($order['shipping_address']); ?>
            </p>
        </div>
    </div>

    <!-- Right Column: Status Modifier & Payment Summary -->
    <div>
        <!-- Status Modifier Form -->
        <div style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 20px; margin-bottom: 20px;">
            <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 14px;">Update Status</h3>
            <form action="order-details.php?id=<?php echo $orderId; ?>" method="POST">
                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Order Status</label>
                    <select name="status" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; background: #fff;">
                        <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="processing" <?php echo $order['status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                        <option value="shipped" <?php echo $order['status'] === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                        <option value="delivered" <?php echo $order['status'] === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                        <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Payment Status</label>
                    <select name="payment_status" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; background: #fff;">
                        <option value="pending" <?php echo $order['payment_status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="paid" <?php echo $order['payment_status'] === 'paid' ? 'selected' : ''; ?>>Paid</option>
                        <option value="refunded" <?php echo $order['payment_status'] === 'refunded' ? 'selected' : ''; ?>>Refunded</option>
                        <option value="failed" <?php echo $order['payment_status'] === 'failed' ? 'selected' : ''; ?>>Failed</option>
                    </select>
                </div>

                <button type="submit" name="update_order_workflow" class="btn btn-primary btn-block">
                    Update Order
                </button>
            </form>
        </div>

        <!-- Financial Summary -->
        <div style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 20px;">
            <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 14px;">Payment Summary</h3>
            <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 8px;">
                <span>Subtotal:</span>
                <span>$<?php echo number_format($order['subtotal'], 2); ?></span>
            </div>
            <?php if ($order['discount_amount'] > 0): ?>
            <div style="display: flex; justify-content: space-between; font-size: 13px; color: #10b981; margin-bottom: 8px;">
                <span>Discount:</span>
                <span>-$<?php echo number_format($order['discount_amount'], 2); ?></span>
            </div>
            <?php endif; ?>
            <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 8px;">
                <span>Tax (5%):</span>
                <span>$<?php echo number_format($order['tax_amount'], 2); ?></span>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 12px;">
                <span>Shipping:</span>
                <span><?php echo $order['shipping_amount'] == 0 ? 'FREE' : '$'.number_format($order['shipping_amount'], 2); ?></span>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 16px; font-weight: 800; border-top: 1px solid #e2e8f0; padding-top: 10px;">
                <span>Total Amount:</span>
                <span style="color: #2563eb;">$<?php echo number_format($order['total'], 2); ?></span>
            </div>
            <div style="margin-top: 14px; font-size: 12px; color: #64748b;">
                Method: <strong><?php echo strtoupper($order['payment_method']); ?></strong>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
