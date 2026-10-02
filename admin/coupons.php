<?php
$adminTitle = 'Coupons & Promo Codes';
$activeMenu = 'coupons';

require_once 'header.php';

$success = '';
$error = '';

// Handle coupon creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_coupon'])) {
    $code           = strtoupper(trim($_POST['code'] ?? ''));
    $description    = trim($_POST['description'] ?? '');
    $discountType   = $_POST['discount_type'] ?? 'percentage';
    $discountValue  = (float)($_POST['discount_value'] ?? 0);
    $minAmount      = (float)($_POST['minimum_amount'] ?? 0);
    $maxDiscount    = !empty($_POST['maximum_discount']) ? (float)$_POST['maximum_discount'] : null;
    $usageLimit     = !empty($_POST['usage_limit']) ? (int)$_POST['usage_limit'] : null;
    $status         = $_POST['status'] ?? 'active';

    if (empty($code) || $discountValue <= 0) {
        $error = 'Coupon code and a valid discount value are required.';
    } else {
        $check = $pdo->prepare("SELECT id FROM coupons WHERE code = ?");
        $check->execute([$code]);
        if ($check->fetch()) {
            $error = "A coupon with code '$code' already exists.";
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO coupons 
                (code, description, discount_type, discount_value, minimum_amount, maximum_discount, usage_limit, status, valid_from, valid_until, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 1 YEAR), NOW())
            ");
            $stmt->execute([
                $code, $description, $discountType, $discountValue, 
                $minAmount, $maxDiscount, $usageLimit, $status
            ]);
            $success = "Coupon '$code' created successfully!";
        }
    }
}

// Handle status toggle
if (isset($_GET['toggle'])) {
    $toggleId = (int)$_GET['toggle'];
    $stmt = $pdo->prepare("UPDATE coupons SET status = IF(status = 'active', 'inactive', 'active') WHERE id = ?");
    $stmt->execute([$toggleId]);
    $success = 'Coupon status updated successfully!';
}

// Handle coupon deletion
if (isset($_GET['delete'])) {
    $delId = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM coupons WHERE id = ?");
    $stmt->execute([$delId]);
    $success = 'Coupon deleted successfully!';
}

$couponsStmt = $pdo->query("SELECT * FROM coupons ORDER BY id DESC");
$coupons = $couponsStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
    <div>
        <h1 style="font-family: 'Poppins', sans-serif; font-size: 24px; font-weight: 700; color: #0f172a;">Manage Coupons & Discounts</h1>
        <p style="font-size: 13px; color: #64748b;">Create marketing promo codes to boost conversions and reward loyal customers</p>
    </div>
</div>

<?php if ($success): ?>
    <div style="background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
        <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div style="background: #fef2f2; color: #b91c1c; border: 1px solid #f87171; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
        <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px; align-items: start;">
    <!-- Add Coupon Form -->
    <div style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 16px;">Create New Coupon</h3>

        <form action="coupons.php" method="POST">
            <input type="hidden" name="save_coupon" value="1">

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 4px;">Coupon Code *</label>
                <input type="text" name="code" required placeholder="e.g. FLASH20" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; text-transform: uppercase;">
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 4px;">Description</label>
                <input type="text" name="description" placeholder="e.g. 20% off all Electronics" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 4px;">Discount Type *</label>
                    <select name="discount_type" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; background: #fff;">
                        <option value="percentage">Percentage (%)</option>
                        <option value="fixed">Fixed Flat ($)</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 4px;">Discount Value *</label>
                    <input type="number" step="0.01" name="discount_value" required placeholder="10.00" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 4px;">Min Order ($)</label>
                    <input type="number" step="0.01" name="minimum_amount" value="50.00" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                </div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 4px;">Max Discount ($)</label>
                    <input type="number" step="0.01" name="maximum_discount" placeholder="Optional" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                </div>
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 4px;">Status</label>
                <select name="status" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; background: #fff;">
                    <option value="active">Active & Available</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary btn-sm btn-block">
                <i class="fas fa-plus-circle"></i> Save Coupon Code
            </button>
        </form>
    </div>

    <!-- Coupons List Table -->
    <div class="admin-table-card">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Code & Description</th>
                    <th>Discount</th>
                    <th>Min. Spend</th>
                    <th>Used</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($coupons)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 24px; color: #94a3b8;">No coupons created yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($coupons as $c): ?>
                    <?php 
                        $isActive = ($c['status'] === 'active');
                    ?>
                    <tr>
                        <td>
                            <strong style="font-family: monospace; font-size: 14px; color: #2563eb; background: #eff6ff; padding: 2px 6px; border-radius: 4px;">
                                <?php echo htmlspecialchars($c['code']); ?>
                            </strong>
                            <?php if (!empty($c['description'])): ?>
                                <div style="font-size: 12px; color: #64748b; margin-top: 2px;"><?php echo htmlspecialchars($c['description']); ?></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong>
                                <?php echo $c['discount_type'] === 'percentage' ? (float)$c['discount_value'] . '%' : '$' . number_format($c['discount_value'], 2); ?>
                            </strong>
                            <?php if (!empty($c['maximum_discount'])): ?>
                                <div style="font-size: 11px; color: #64748b;">(Up to $<?php echo number_format($c['maximum_discount'], 2); ?>)</div>
                            <?php endif; ?>
                        </td>
                        <td>
                            $<?php echo number_format($c['minimum_amount'], 2); ?>
                        </td>
                        <td>
                            <?php echo (int)($c['used_count'] ?? 0); ?> times
                        </td>
                        <td>
                            <a href="coupons.php?toggle=<?php echo $c['id']; ?>" title="Click to toggle status">
                                <span style="display: inline-block; padding: 2px 8px; border-radius: 99px; font-size: 11px; font-weight: 700; text-transform: uppercase; <?php echo $isActive ? 'background: #ecfdf5; color: #065f46;' : 'background: #f1f5f9; color: #64748b;'; ?>">
                                    <?php echo $c['status']; ?>
                                </span>
                            </a>
                        </td>
                        <td style="text-align: right;">
                            <a href="coupons.php?delete=<?php echo $c['id']; ?>" class="btn btn-outline btn-sm" style="color: #ef4444; border-color: #fca5a5;" onclick="return confirm('Delete this coupon?');">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'footer.php'; ?>
