<?php
$adminTitle = 'Dashboard Overview';
$activeMenu = 'dashboard';

require_once 'header.php';

$stats = getAdminStats();
$recentOrders = getRecentOrders(8);
$lowStock = getLowStockProducts(10);
?>

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
    <div>
        <h1 style="font-family: 'Poppins', sans-serif; font-size: 24px; font-weight: 700; color: #0f172a;">Executive Dashboard</h1>
        <p style="font-size: 13px; color: #64748b;">Overview of sales, inventory, and recent customer activity</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="product-form.php" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Product</a>
        <a href="orders.php" class="btn btn-outline btn-sm"><i class="fas fa-shopping-cart"></i> View All Orders</a>
    </div>
</div>

<!-- KPI Cards Grid -->
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 28px;">
    <div class="kpi-card">
        <div>
            <span style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">Total Sales Revenue</span>
            <div style="font-size: 26px; font-weight: 800; color: #0f172a; margin-top: 4px;">
                $<?php echo number_format($stats['total_revenue'], 2); ?>
            </div>
            <span style="font-size: 11px; color: #10b981; font-weight: 600;"><i class="fas fa-arrow-up"></i> Active store earnings</span>
        </div>
        <div class="kpi-icon-box" style="background: #eff6ff; color: #2563eb;">
            <i class="fas fa-dollar-sign"></i>
        </div>
    </div>

    <div class="kpi-card">
        <div>
            <span style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">Total Orders</span>
            <div style="font-size: 26px; font-weight: 800; color: #0f172a; margin-top: 4px;">
                <?php echo $stats['total_orders']; ?>
            </div>
            <span style="font-size: 11px; color: <?php echo $stats['pending_orders'] > 0 ? '#f59e0b' : '#64748b'; ?>; font-weight: 600;">
                <?php echo $stats['pending_orders']; ?> pending shipment
            </span>
        </div>
        <div class="kpi-icon-box" style="background: #fdf2f8; color: #ec4899;">
            <i class="fas fa-shopping-bag"></i>
        </div>
    </div>

    <div class="kpi-card">
        <div>
            <span style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">Active Products</span>
            <div style="font-size: 26px; font-weight: 800; color: #0f172a; margin-top: 4px;">
                <?php echo $stats['total_products']; ?>
            </div>
            <span style="font-size: 11px; color: #64748b;">In catalog</span>
        </div>
        <div class="kpi-icon-box" style="background: #ecfdf5; color: #10b981;">
            <i class="fas fa-boxes"></i>
        </div>
    </div>

    <div class="kpi-card">
        <div>
            <span style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase;">Registered Customers</span>
            <div style="font-size: 26px; font-weight: 800; color: #0f172a; margin-top: 4px;">
                <?php echo $stats['total_users']; ?>
            </div>
            <span style="font-size: 11px; color: #2563eb; font-weight: 600;">Active community</span>
        </div>
        <div class="kpi-icon-box" style="background: #fef3c7; color: #d97706;">
            <i class="fas fa-users"></i>
        </div>
    </div>
</div>

<!-- Low Inventory Warning Banner -->
<?php if (!empty($lowStock)): ?>
<div style="background: #fffbeb; border: 1px solid #fcd34d; border-radius: 12px; padding: 18px 22px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between;">
    <div style="display: flex; align-items: center; gap: 14px;">
        <i class="fas fa-exclamation-triangle" style="font-size: 24px; color: #d97706;"></i>
        <div>
            <h4 style="font-size: 14px; font-weight: 700; color: #92400e; margin-bottom: 2px;">
                Low Stock Inventory Alert (<?php echo count($lowStock); ?> products)
            </h4>
            <p style="font-size: 12px; color: #78350f;">
                Some products have 10 or fewer units remaining. Restock to prevent lost sales.
            </p>
        </div>
    </div>
    <a href="products.php" class="btn btn-outline btn-sm" style="border-color: #d97706; color: #92400e;">Manage Stock</a>
</div>
<?php endif; ?>

<!-- Recent Orders Table -->
<div class="admin-table-card">
    <div style="padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e2e8f0;">
        <h3 style="font-size: 15px; font-weight: 700; color: #0f172a;">Recent Orders</h3>
        <a href="orders.php" style="font-size: 12px; font-weight: 600; color: #2563eb;">View All Orders &rarr;</a>
    </div>

    <?php if (empty($recentOrders)): ?>
        <p style="padding: 30px; text-align: center; color: #94a3b8;">No orders found yet.</p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentOrders as $ord): ?>
                <?php 
                    $badgeStyle = 'background: #eff6ff; color: #1d4ed8;';
                    if ($ord['status'] === 'delivered') $badgeStyle = 'background: #ecfdf5; color: #065f46;';
                    if ($ord['status'] === 'cancelled') $badgeStyle = 'background: #fef2f2; color: #991b1b;';
                    if ($ord['status'] === 'shipped') $badgeStyle = 'background: #f5f3ff; color: #5b21b6;';
                ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($ord['order_number']); ?></strong></td>
                    <td>
                        <div><?php echo htmlspecialchars($ord['username'] ?? 'Guest'); ?></div>
                        <span style="font-size: 11px; color: #64748b;"><?php echo htmlspecialchars($ord['email'] ?? ''); ?></span>
                    </td>
                    <td><?php echo date('M j, Y H:i', strtotime($ord['created_at'])); ?></td>
                    <td><strong>$<?php echo number_format($ord['total'], 2); ?></strong></td>
                    <td>
                        <span style="font-size: 11px; font-weight: 700; text-transform: uppercase;">
                            <?php echo htmlspecialchars($ord['payment_method']); ?> (<?php echo htmlspecialchars($ord['payment_status']); ?>)
                        </span>
                    </td>
                    <td>
                        <span style="display: inline-block; padding: 2px 8px; border-radius: 99px; font-size: 11px; font-weight: 700; text-transform: uppercase; <?php echo $badgeStyle; ?>">
                            <?php echo htmlspecialchars($ord['status']); ?>
                        </span>
                    </td>
                    <td>
                        <a href="order-details.php?id=<?php echo $ord['id']; ?>" class="btn btn-outline btn-sm">
                            <i class="far fa-eye"></i> View
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require_once 'footer.php'; ?>
