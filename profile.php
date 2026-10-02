<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

requireLogin();

$userId = $_SESSION['user_id'];
$user = getUser($userId);
$tab = $_GET['tab'] ?? 'profile';

$successMsg = '';
$errorMsg = '';

// Handle Profile Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName  = trim($_POST['last_name'] ?? '');
    $phone     = trim($_POST['phone'] ?? '');
    $address   = trim($_POST['address'] ?? '');
    $city      = trim($_POST['city'] ?? '');
    $state     = trim($_POST['state'] ?? '');
    $zipCode   = trim($_POST['zip_code'] ?? '');
    $country   = trim($_POST['country'] ?? 'India');

    updateUserProfile($userId, $firstName, $lastName, $phone, $address, $city, $state, $zipCode, $country);
    $user = getUser($userId);
    $_SESSION['full_name'] = trim($firstName . ' ' . $lastName) ?: $user['username'];
    $successMsg = 'Profile updated successfully!';
}

// Handle Order Cancellation
if (isset($_GET['cancel_order'])) {
    $cancelOrderId = (int)$_GET['cancel_order'];
    if (cancelOrder($cancelOrderId, $userId)) {
        $successMsg = 'Order #' . $cancelOrderId . ' has been cancelled.';
    } else {
        $errorMsg = 'Could not cancel order. It may have already been shipped.';
    }
}

// Handle Password Change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $currentPass = $_POST['current_password'] ?? '';
    $newPass     = $_POST['new_password'] ?? '';
    $confirmPass = $_POST['confirm_password'] ?? '';

    if (empty($currentPass) || empty($newPass)) {
        $errorMsg = 'Please fill in all password fields.';
    } elseif ($newPass !== $confirmPass) {
        $errorMsg = 'New passwords do not match.';
    } elseif (strlen($newPass) < 6) {
        $errorMsg = 'Password must be at least 6 characters long.';
    } elseif (!password_verify($currentPass, $user['password'])) {
        $errorMsg = 'Your current password is incorrect.';
    } else {
        $hashed = password_hash($newPass, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$hashed, $userId]);
        $successMsg = 'Password changed successfully!';
    }
}

$userOrders = getUserOrders($userId);
$pageTitle = 'My Account & Orders - ShopEasy';
$activeNav = 'profile';

require_once 'includes/header.php';
?>

<main class="profile-page-container" style="padding: 24px 0 60px;">
    <div class="container">
        <!-- Breadcrumbs -->
        <nav class="catalog-breadcrumb" aria-label="breadcrumb">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <i class="fas fa-chevron-right"></i>
            <span>My Account</span>
        </nav>

        <div style="display: grid; grid-template-columns: 260px 1fr; gap: 28px; align-items: start;">
            <!-- Left Sidebar Navigation Menu -->
            <aside style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: var(--shadow-xs);">
                <div style="padding: 24px 20px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; text-align: center;">
                    <div style="width: 64px; height: 64px; border-radius: 50%; background: #2563eb; color: #fff; font-size: 26px; font-weight: 800; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3);">
                        <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                    </div>
                    <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 2px;">
                        <?php echo htmlspecialchars(trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?: $user['username']); ?>
                    </h3>
                    <span style="font-size: 12px; color: #64748b;"><?php echo htmlspecialchars($user['email']); ?></span>
                </div>

                <ul style="padding: 10px 0;">
                    <li>
                        <a href="profile.php?tab=profile" style="display: flex; align-items: center; gap: 12px; padding: 12px 20px; font-size: 13px; font-weight: 600; color: <?php echo $tab === 'profile' ? '#2563eb' : '#334155'; ?>; background: <?php echo $tab === 'profile' ? '#eff6ff' : 'transparent'; ?>;">
                            <i class="fas fa-user"></i> My Profile
                        </a>
                    </li>
                    <li>
                        <a href="profile.php?tab=orders" style="display: flex; align-items: center; gap: 12px; padding: 12px 20px; font-size: 13px; font-weight: 600; color: <?php echo $tab === 'orders' ? '#2563eb' : '#334155'; ?>; background: <?php echo $tab === 'orders' ? '#eff6ff' : 'transparent'; ?>;">
                            <i class="fas fa-box-open"></i> My Orders (<?php echo count($userOrders); ?>)
                        </a>
                    </li>
                    <li>
                        <a href="wishlist.php" style="display: flex; align-items: center; gap: 12px; padding: 12px 20px; font-size: 13px; font-weight: 600; color: #334155;">
                            <i class="fas fa-heart"></i> My Wishlist
                        </a>
                    </li>
                    <li>
                        <a href="profile.php?tab=addresses" style="display: flex; align-items: center; gap: 12px; padding: 12px 20px; font-size: 13px; font-weight: 600; color: <?php echo $tab === 'addresses' ? '#2563eb' : '#334155'; ?>; background: <?php echo $tab === 'addresses' ? '#eff6ff' : 'transparent'; ?>;">
                            <i class="fas fa-map-marker-alt"></i> Saved Addresses
                        </a>
                    </li>
                    <li>
                        <a href="profile.php?tab=password" style="display: flex; align-items: center; gap: 12px; padding: 12px 20px; font-size: 13px; font-weight: 600; color: <?php echo $tab === 'password' ? '#2563eb' : '#334155'; ?>; background: <?php echo $tab === 'password' ? '#eff6ff' : 'transparent'; ?>;">
                            <i class="fas fa-key"></i> Change Password
                        </a>
                    </li>
                    <?php if (isAdmin()): ?>
                    <li style="border-top: 1px solid #f1f5f9; margin-top: 6px; padding-top: 6px;">
                        <a href="admin/index.php" style="display: flex; align-items: center; gap: 12px; padding: 12px 20px; font-size: 13px; font-weight: 700; color: #2563eb;">
                            <i class="fas fa-shield-alt"></i> Admin Control Panel
                        </a>
                    </li>
                    <?php endif; ?>
                    <li style="border-top: 1px solid #f1f5f9; margin-top: 6px; padding-top: 6px;">
                        <a href="logout.php" style="display: flex; align-items: center; gap: 12px; padding: 12px 20px; font-size: 13px; font-weight: 600; color: #ef4444;">
                            <i class="fas fa-sign-out-alt"></i> Sign Out
                        </a>
                    </li>
                </ul>
            </aside>

            <!-- Right Content Area -->
            <section style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 28px; box-shadow: var(--shadow-xs);">
                <?php if ($successMsg): ?>
                    <div style="background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($successMsg); ?>
                    </div>
                <?php endif; ?>

                <?php if ($errorMsg): ?>
                    <div style="background: #fef2f2; color: #b91c1c; border: 1px solid #f87171; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($errorMsg); ?>
                    </div>
                <?php endif; ?>

                <!-- Tab: My Orders (Amazon / Flipkart Order History) -->
                <?php if ($tab === 'orders'): ?>
                    <h2 style="font-size: 20px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">My Orders History</h2>
                    <p style="font-size: 13px; color: #64748b; margin-bottom: 24px;">Track shipments, review past purchases, or cancel orders</p>

                    <?php if (empty($userOrders)): ?>
                        <div style="text-align: center; padding: 40px 20px;">
                            <i class="fas fa-box-open" style="font-size: 48px; color: #cbd5e1; margin-bottom: 12px;"></i>
                            <h4 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">No orders found</h4>
                            <p style="font-size: 13px; color: #64748b; margin-bottom: 18px;">You haven't placed any orders yet.</p>
                            <a href="products.php" class="btn btn-primary btn-sm">Start Shopping</a>
                        </div>
                    <?php else: ?>
                        <div style="display: flex; flex-direction: column; gap: 16px;">
                            <?php foreach ($userOrders as $ord): ?>
                            <?php 
                                $statusBadgeColor = '#2563eb';
                                if ($ord['status'] === 'delivered') $statusBadgeColor = '#10b981';
                                if ($ord['status'] === 'cancelled') $statusBadgeColor = '#ef4444';
                                if ($ord['status'] === 'shipped') $statusBadgeColor = '#8b5cf6';
                            ?>
                            <div style="border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden;">
                                <div style="background: #f8fafc; padding: 14px 20px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e2e8f0; flex-wrap: wrap; gap: 12px;">
                                    <div style="display: flex; gap: 24px;">
                                        <div>
                                            <span style="font-size: 11px; color: #64748b; text-transform: uppercase;">Order Placed</span>
                                            <div style="font-size: 13px; font-weight: 700; color: #0f172a;"><?php echo date('M j, Y', strtotime($ord['created_at'])); ?></div>
                                        </div>
                                        <div>
                                            <span style="font-size: 11px; color: #64748b; text-transform: uppercase;">Total</span>
                                            <div style="font-size: 13px; font-weight: 700; color: #0f172a;">$<?php echo number_format($ord['total'], 2); ?></div>
                                        </div>
                                        <div>
                                            <span style="font-size: 11px; color: #64748b; text-transform: uppercase;">Status</span>
                                            <div>
                                                <span style="display: inline-block; background: <?php echo $statusBadgeColor; ?>; color: #fff; font-size: 10px; font-weight: 800; padding: 2px 8px; border-radius: 99px; text-transform: uppercase;">
                                                    <?php echo htmlspecialchars($ord['status']); ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="text-align: right;">
                                        <span style="font-size: 11px; color: #64748b; text-transform: uppercase;">Order #</span>
                                        <div style="font-size: 13px; font-weight: 700; color: #0f172a;"><?php echo htmlspecialchars($ord['order_number']); ?></div>
                                    </div>
                                </div>

                                <div style="padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
                                    <div>
                                        <h4 style="font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">
                                            Delivering to <?php echo htmlspecialchars(explode("\n", $ord['shipping_address'])[0] ?? 'Address'); ?>
                                        </h4>
                                        <p style="font-size: 12px; color: #64748b;">
                                            Payment: <?php echo strtoupper($ord['payment_method']); ?> &bull; Status: <?php echo ucfirst($ord['payment_status']); ?>
                                        </p>
                                    </div>
                                    <div style="display: flex; gap: 10px;">
                                        <a href="order-confirmation.php?order_id=<?php echo $ord['id']; ?>" class="btn btn-outline btn-sm">
                                            <i class="fas fa-receipt"></i> View Invoice
                                        </a>
                                        <?php if (in_array($ord['status'], ['pending', 'processing'])): ?>
                                            <a href="profile.php?tab=orders&cancel_order=<?php echo $ord['id']; ?>" class="btn btn-outline btn-sm" style="color: #ef4444; border-color: #fca5a5;" onclick="return confirm('Are you sure you want to cancel this order?');">
                                                Cancel Order
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                <!-- Tab: My Profile -->
                <?php elseif ($tab === 'profile'): ?>
                    <h2 style="font-size: 20px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">Personal Information</h2>
                    <p style="font-size: 13px; color: #64748b; margin-bottom: 24px;">Manage your name, contact details, and default delivery information</p>

                    <form action="profile.php?tab=profile" method="POST">
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 16px;">
                            <div>
                                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">First Name</label>
                                <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                            </div>
                            <div>
                                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Last Name</label>
                                <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 16px;">
                            <div>
                                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Email Address (Primary)</label>
                                <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; background: #f8fafc; border-radius: 8px; font-size: 13px; color: #64748b;">
                            </div>
                            <div>
                                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Phone Number</label>
                                <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                            </div>
                        </div>

                        <div style="margin-bottom: 16px;">
                            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Primary Address</label>
                            <textarea name="address" rows="2" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; resize: vertical;"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
                            <div>
                                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">City</label>
                                <input type="text" name="city" value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                            </div>
                            <div>
                                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">State / Province</label>
                                <input type="text" name="state" value="<?php echo htmlspecialchars($user['state'] ?? ''); ?>" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                            </div>
                            <div>
                                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Pincode / ZIP</label>
                                <input type="text" name="zip_code" value="<?php echo htmlspecialchars($user['zip_code'] ?? ''); ?>" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                            </div>
                        </div>

                        <button type="submit" name="update_profile" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Profile Details
                        </button>
                    </form>

                <!-- Tab: Saved Addresses -->
                <?php elseif ($tab === 'addresses'): ?>
                    <h2 style="font-size: 20px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">Saved Addresses</h2>
                    <p style="font-size: 13px; color: #64748b; margin-bottom: 24px;">Your verified delivery destinations</p>

                    <div style="border: 2px solid #2563eb; background: #eff6ff; border-radius: 12px; padding: 20px; max-width: 480px; position: relative;">
                        <span style="position: absolute; top: 14px; right: 14px; background: #2563eb; color: #fff; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 99px;">DEFAULT</span>
                        <h4 style="font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">
                            <?php echo htmlspecialchars(trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?: $user['username']); ?>
                        </h4>
                        <p style="font-size: 13px; color: #334155; line-height: 1.6;">
                            <?php echo htmlspecialchars($user['address'] ?? 'No address saved yet.'); ?><br>
                            <?php echo htmlspecialchars($user['city'] ?? ''); ?>, <?php echo htmlspecialchars($user['state'] ?? ''); ?> - <?php echo htmlspecialchars($user['zip_code'] ?? ''); ?><br>
                            Phone: <?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?>
                        </p>
                        <div style="margin-top: 14px;">
                            <a href="profile.php?tab=profile" class="btn btn-outline btn-sm">Edit Address</a>
                        </div>
                    </div>

                <!-- Tab: Change Password -->
                <?php elseif ($tab === 'password'): ?>
                    <h2 style="font-size: 20px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">Change Password</h2>
                    <p style="font-size: 13px; color: #64748b; margin-bottom: 24px;">Keep your account secure with a strong password</p>

                    <form action="profile.php?tab=password" method="POST" style="max-width: 420px;">
                        <div style="margin-bottom: 16px;">
                            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Current Password *</label>
                            <input type="password" name="current_password" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                        </div>
                        <div style="margin-bottom: 16px;">
                            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">New Password *</label>
                            <input type="password" name="new_password" required minlength="6" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                        </div>
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Confirm New Password *</label>
                            <input type="password" name="confirm_password" required minlength="6" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                        </div>
                        <button type="submit" name="change_password" class="btn btn-primary">
                            <i class="fas fa-lock"></i> Update Password
                        </button>
                    </form>
                <?php endif; ?>
            </section>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>