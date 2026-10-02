<?php
$adminTitle = 'Order Management';
$activeMenu = 'orders';

require_once 'header.php';

$statusFilter = trim($_GET['status'] ?? '');
$search = trim($_GET['search'] ?? '');

// Handle status change
$msg = '';
if (isset($_POST['update_status'])) {
    $orderId = (int)$_POST['order_id'];
    $newStatus = $_POST['status'];
    $newPayment = $_POST['payment_status'];
    updateOrderStatus($orderId, $newStatus, $newPayment);
    $msg = 'Order #' . $orderId . ' status updated to ' . strtoupper($newStatus);
}

// Build query
$sql = "
    SELECT o.*, u.username, u.email, COUNT(oi.id) as item_count 
    FROM orders o 
    LEFT JOIN users u ON o.user_id = u.id 
    LEFT JOIN order_items oi ON o.id = oi.order_id 
    WHERE 1=1
";
$args = [];

if ($statusFilter) {
    $sql .= " AND o.status = ?";
    $args[] = $statusFilter;
}

if ($search) {
    $sql .= " AND (o.order_number LIKE ? OR u.username LIKE ? OR u.email LIKE ?)";
    $args[] = "%$search%";
    $args[] = "%$search%";
    $args[] = "%$search%";
}

$sql .= " GROUP BY o.id ORDER BY o.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($args);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
    <div>
        <h1 style="font-family: 'Poppins', sans-serif; font-size: 24px; font-weight: 700; color: #0f172a;">Customer Orders</h1>
        <p style="font-size: 13px; color: #64748b;">Process orders, fulfill deliveries, and update shipment tracking</p>
    </div>
</div>

<?php if ($msg): ?>
    <div style="background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
        <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($msg); ?>
    </div>
<?php endif; ?>

<!-- Filter & Search Toolbar -->
<div style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 16px 20px; margin-bottom: 20px; display: flex; gap: 14px; flex-wrap: wrap; align-items: center;">
    <form action="orders.php" method="GET" style="display: flex; gap: 12px; flex: 1;">
        <input type="text" name="search" placeholder="Search by Order # or Customer..." value="<?php echo htmlspecialchars($search); ?>" style="flex: 1; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
        
        <select name="status" style="padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; background: #fff;">
            <option value="">All Statuses</option>
            <option value="pending" <?php echo $statusFilter === 'pending' ? 'selected' : ''; ?>>Pending</option>
            <option value="processing" <?php echo $statusFilter === 'processing' ? 'selected' : ''; ?>>Processing</option>
            <option value="shipped" <?php echo $statusFilter === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
            <option value="delivered" <?php echo $statusFilter === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
            <option value="cancelled" <?php echo $statusFilter === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
        </select>

        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        <?php if ($search || $statusFilter): ?>
            <a href="orders.php" class="btn btn-outline btn-sm">Clear</a>
        <?php endif; ?>
    </form>
</div>

<!-- Orders Table -->
<div class="admin-table-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Items</th>
                <th>Total ($)</th>
                <th>Payment</th>
                <th>Order Status</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($orders)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 30px; color: #94a3b8;">No orders found matching criteria.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($orders as $ord): ?>
                <tr>
                    <td>
                        <strong><?php echo htmlspecialchars($ord['order_number']); ?></strong>
                        <div style="font-size: 11px; color: #64748b;"><?php echo date('M j, Y H:i', strtotime($ord['created_at'])); ?></div>
                    </td>
                    <td>
                        <div><?php echo htmlspecialchars($ord['username'] ?? 'Customer'); ?></div>
                        <span style="font-size: 11px; color: #64748b;"><?php echo htmlspecialchars($ord['email'] ?? ''); ?></span>
                    </td>
                    <td><?php echo $ord['item_count']; ?> items</td>
                    <td><strong>$<?php echo number_format($ord['total'], 2); ?></strong></td>
                    <td>
                        <span style="font-size: 11px; font-weight: 700; text-transform: uppercase;">
                            <?php echo htmlspecialchars($ord['payment_method']); ?>
                        </span>
                        <div style="font-size: 10px; color: <?php echo $ord['payment_status'] === 'paid' ? '#10b981' : '#f59e0b'; ?>; font-weight: 700;">
                            <?php echo strtoupper($ord['payment_status']); ?>
                        </div>
                    </td>
                    <td>
                        <form action="orders.php" method="POST" style="display: flex; gap: 6px; align-items: center;">
                            <input type="hidden" name="order_id" value="<?php echo $ord['id']; ?>">
                            <input type="hidden" name="payment_status" value="<?php echo htmlspecialchars($ord['payment_status']); ?>">
                            <select name="status" onchange="this.form.submit()" style="padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; border: 1px solid #cbd5e1; background: #fff;">
                                <option value="pending" <?php echo $ord['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="processing" <?php echo $ord['status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                <option value="shipped" <?php echo $ord['status'] === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                <option value="delivered" <?php echo $ord['status'] === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                <option value="cancelled" <?php echo $ord['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                            <input type="hidden" name="update_status" value="1">
                        </form>
                    </td>
                    <td style="text-align: right;">
                        <a href="order-details.php?id=<?php echo $ord['id']; ?>" class="btn btn-outline btn-sm">
                            <i class="far fa-eye"></i> Details
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once 'footer.php'; ?>
