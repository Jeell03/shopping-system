<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// Require user login for checkout
if (!isLoggedIn()) {
    $_SESSION['redirect_after_login'] = 'checkout.php';
    flash('Please sign in or create an account to proceed to checkout.', 'info');
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];
$currentUser = getUser($userId);
$cartBreakdown = getCartBreakdown();

if (empty($cartBreakdown['items']) || $cartBreakdown['subtotal'] <= 0) {
    header('Location: cart.php');
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $shippingFullName = trim($_POST['shipping_name'] ?? '');
    $shippingPhone    = trim($_POST['shipping_phone'] ?? '');
    $shippingAddress  = trim($_POST['shipping_address'] ?? '');
    $shippingCity     = trim($_POST['shipping_city'] ?? '');
    $shippingState    = trim($_POST['shipping_state'] ?? '');
    $shippingZip      = trim($_POST['shipping_zip'] ?? '');
    $shippingCountry  = trim($_POST['shipping_country'] ?? 'India');
    $deliveryOption   = trim($_POST['delivery_option'] ?? 'standard');
    $paymentMethod    = trim($_POST['payment_method'] ?? 'cod');

    if (empty($shippingFullName) || empty($shippingAddress) || empty($shippingCity) || empty($shippingZip)) {
        $error = 'Please complete all required delivery address fields.';
    } else {
        $fullShippingFormatted = "$shippingFullName\n$shippingAddress\n$shippingCity, $shippingState - $shippingZip\n$shippingCountry\nPhone: $shippingPhone";
        $fullBillingFormatted = $fullShippingFormatted;

        $shippingFee = ($deliveryOption === 'express') ? 9.99 : $cartBreakdown['shipping'];
        $finalTotal = round(($cartBreakdown['subtotal'] - $cartBreakdown['discount']) + $shippingFee + $cartBreakdown['tax'], 2);

        $orderResult = createOrder(
            $userId,
            $cartBreakdown['items'],
            $finalTotal,
            $fullShippingFormatted,
            $fullBillingFormatted,
            $paymentMethod,
            $cartBreakdown['subtotal'],
            $cartBreakdown['tax'],
            $shippingFee,
            $cartBreakdown['discount']
        );

        if ($orderResult && !empty($orderResult['order_id'])) {
            // Save address to user profile if blank
            if (empty($currentUser['address'])) {
                updateUserProfile($userId, $currentUser['first_name'], $currentUser['last_name'], $shippingPhone, $shippingAddress, $shippingCity, $shippingState, $shippingZip, $shippingCountry);
            }

            clearCart();
            header('Location: order-confirmation.php?order_id=' . $orderResult['order_id']);
            exit();
        } else {
            $error = 'There was a technical issue placing your order. Please try again.';
        }
    }
}

$pageTitle = 'Secure Checkout - ShopEasy';
$activeNav = 'checkout';

require_once 'includes/header.php';
?>

<main class="checkout-page-container" style="padding: 24px 0 60px;">
    <div class="container">
        <!-- Breadcrumbs -->
        <nav class="catalog-breadcrumb" aria-label="breadcrumb">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <i class="fas fa-chevron-right"></i>
            <a href="cart.php">Shopping Cart</a>
            <i class="fas fa-chevron-right"></i>
            <span>Secure Checkout</span>
        </nav>

        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
            <h1 style="font-family: 'Poppins', sans-serif; font-size: 24px; font-weight: 700; color: #0f172a;">
                <i class="fas fa-lock" style="color: #10b981;"></i> 256-Bit SSL Secure Checkout
            </h1>
            <span style="font-size: 13px; color: #64748b;">Logged in as <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
        </div>

        <?php if ($error): ?>
            <div style="background: #fef2f2; color: #b91c1c; border: 1px solid #f87171; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="checkout.php" method="POST" id="checkoutMasterForm">
            <div class="cart-split-layout">
                <!-- Left Column: 4-Step Checkout Accordion -->
                <div class="checkout-steps-col">
                    <!-- Step 1: Delivery Address -->
                    <div class="checkout-step-card">
                        <div class="step-card-header">
                            <span class="step-number-badge">1</span>
                            <div>
                                <h3 style="font-size: 16px; font-weight: 700; color: #0f172a;">Delivery Address</h3>
                                <p style="font-size: 12px; color: #64748b;">Select where you want your order delivered</p>
                            </div>
                        </div>
                        <div class="step-card-body">
                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 16px;">
                                <div>
                                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Full Name *</label>
                                    <input type="text" name="shipping_name" value="<?php echo htmlspecialchars(trim(($currentUser['first_name'] ?? '') . ' ' . ($currentUser['last_name'] ?? '')) ?: $currentUser['username']); ?>" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                                </div>
                                <div>
                                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Phone Number *</label>
                                    <input type="tel" name="shipping_phone" value="<?php echo htmlspecialchars($currentUser['phone'] ?? '+91 9876543210'); ?>" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                                </div>
                            </div>

                            <div style="margin-bottom: 16px;">
                                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Street Address / Apartment *</label>
                                <textarea name="shipping_address" rows="2" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; resize: vertical;"><?php echo htmlspecialchars($currentUser['address'] ?? 'Flat 402, Lotus Residency, MG Road'); ?></textarea>
                            </div>

                            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px;">
                                <div>
                                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">City *</label>
                                    <input type="text" name="shipping_city" value="<?php echo htmlspecialchars($currentUser['city'] ?? 'Mumbai'); ?>" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                                </div>
                                <div>
                                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">State / Province</label>
                                    <input type="text" name="shipping_state" value="<?php echo htmlspecialchars($currentUser['state'] ?? 'Maharashtra'); ?>" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                                </div>
                                <div>
                                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Pincode / ZIP *</label>
                                    <input type="text" name="shipping_zip" value="<?php echo htmlspecialchars($currentUser['zip_code'] ?? '400001'); ?>" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Delivery Speed Options -->
                    <div class="checkout-step-card">
                        <div class="step-card-header">
                            <span class="step-number-badge">2</span>
                            <div>
                                <h3 style="font-size: 16px; font-weight: 700; color: #0f172a;">Delivery Options</h3>
                                <p style="font-size: 12px; color: #64748b;">Choose your preferred shipping speed</p>
                            </div>
                        </div>
                        <div class="step-card-body">
                            <label class="payment-option-row selected" style="margin-bottom: 12px;">
                                <input type="radio" name="delivery_option" value="standard" checked>
                                <div>
                                    <strong>Standard Delivery (3-5 Business Days)</strong>
                                    <p style="font-size: 12px; color: #64748b; margin-top: 2px;">
                                        Estimated arrival by <strong><?php echo date('D, M j', strtotime('+3 days')); ?></strong> &bull; 
                                        <?php echo $cartBreakdown['shipping'] == 0 ? '<span style="color: #10b981; font-weight: 700;">FREE</span>' : '$10.00'; ?>
                                    </p>
                                </div>
                            </label>

                            <label class="payment-option-row">
                                <input type="radio" name="delivery_option" value="express">
                                <div>
                                    <strong>Express Priority Courier (1-2 Business Days)</strong>
                                    <p style="font-size: 12px; color: #64748b; margin-top: 2px;">
                                        Guaranteed arrival by <strong><?php echo date('D, M j', strtotime('+1 day')); ?></strong> &bull; $9.99
                                    </p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Step 3: Payment Options -->
                    <div class="checkout-step-card">
                        <div class="step-card-header">
                            <span class="step-number-badge">3</span>
                            <div>
                                <h3 style="font-size: 16px; font-weight: 700; color: #0f172a;">Payment Method</h3>
                                <p style="font-size: 12px; color: #64748b;">All transactions are encrypted and secured</p>
                            </div>
                        </div>
                        <div class="step-card-body">
                            <div class="payment-methods-tabs">
                                <!-- Option: Credit / Debit Card -->
                                <label class="payment-option-row" onclick="showPaymentForm('card')">
                                    <input type="radio" name="payment_method" value="card" checked>
                                    <i class="fas fa-credit-card" style="font-size: 20px; color: #2563eb;"></i>
                                    <div>
                                        <strong>Credit or Debit Card</strong>
                                        <div style="font-size: 12px; color: #64748b;">Visa, MasterCard, American Express, RuPay</div>
                                    </div>
                                </label>

                                <div id="cardFieldsBox" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 18px; margin: 4px 0 12px 28px;">
                                    <div style="margin-bottom: 12px;">
                                        <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Card Number</label>
                                        <input type="text" placeholder="4111 2222 3333 4444" maxlength="19" value="4111 2222 3333 4444" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; font-family: monospace;">
                                    </div>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                                        <div>
                                            <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Expiry Date</label>
                                            <input type="text" placeholder="MM/YY" maxlength="5" value="12/28" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                                        </div>
                                        <div>
                                            <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">CVV Security Code</label>
                                            <input type="password" placeholder="123" maxlength="4" value="123" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                                        </div>
                                    </div>
                                </div>

                                <!-- Option: UPI / Net Banking -->
                                <label class="payment-option-row" onclick="showPaymentForm('upi')">
                                    <input type="radio" name="payment_method" value="upi">
                                    <i class="fas fa-qrcode" style="font-size: 20px; color: #10b981;"></i>
                                    <div>
                                        <strong>UPI / Net Banking</strong>
                                        <div style="font-size: 12px; color: #64748b;">Instant payment via Google Pay, PhonePe, Paytm, BHIM</div>
                                    </div>
                                </label>

                                <div id="upiFieldsBox" style="display: none; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 18px; margin: 4px 0 12px 28px;">
                                    <label style="display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px;">Enter your UPI ID (VPA)</label>
                                    <div style="display: flex; gap: 8px;">
                                        <input type="text" placeholder="e.g. mobile@okhdfcbank" style="flex: 1; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                                        <button type="button" class="btn btn-outline btn-sm" onclick="showToast('UPI ID verified successfully!', 'success');">Verify</button>
                                    </div>
                                </div>

                                <!-- Option: Cash on Delivery -->
                                <label class="payment-option-row" onclick="showPaymentForm('cod')">
                                    <input type="radio" name="payment_method" value="cod">
                                    <i class="fas fa-money-bill-wave" style="font-size: 20px; color: #f59e0b;"></i>
                                    <div>
                                        <strong>Cash on Delivery (Pay upon Arrival)</strong>
                                        <div style="font-size: 12px; color: #64748b;">Pay with Cash or UPI when your parcel arrives at your door</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Sticky Order Summary & Submit -->
                <div class="cart-summary-card">
                    <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 16px;">Order Summary</h3>

                    <div style="max-height: 180px; overflow-y: auto; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">
                        <?php foreach ($cartBreakdown['items'] as $it): ?>
                        <div style="display: flex; align-items: center; justify-content: space-between; font-size: 12px; margin-bottom: 8px;">
                            <span style="max-width: 220px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <strong><?php echo $it['quantity']; ?>x</strong> <?php echo htmlspecialchars($it['product']['name']); ?>
                            </span>
                            <span style="font-weight: 700;">$<?php echo number_format($it['subtotal'], 2); ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="summary-line-item">
                        <span>Items Total:</span>
                        <span>$<?php echo number_format($cartBreakdown['mrp_total'], 2); ?></span>
                    </div>

                    <?php if ($cartBreakdown['discount'] > 0): ?>
                    <div class="summary-line-item discount-item">
                        <span>Coupon Discount (<?php echo htmlspecialchars($cartBreakdown['applied_coupon']['code'] ?? ''); ?>):</span>
                        <span>-$<?php echo number_format($cartBreakdown['discount'], 2); ?></span>
                    </div>
                    <?php endif; ?>

                    <div class="summary-line-item">
                        <span>Tax Amount:</span>
                        <span>$<?php echo number_format($cartBreakdown['tax'], 2); ?></span>
                    </div>

                    <div class="summary-line-item">
                        <span>Delivery Speed:</span>
                        <span id="summaryDeliveryFee">
                            <?php echo $cartBreakdown['shipping'] == 0 ? '<strong style="color: #10b981;">FREE</strong>' : '$'.number_format($cartBreakdown['shipping'], 2); ?>
                        </span>
                    </div>

                    <div class="summary-line-item summary-total-line">
                        <span>Order Total:</span>
                        <span id="summaryFinalTotal">$<?php echo number_format($cartBreakdown['total'], 2); ?></span>
                    </div>

                    <button type="submit" name="place_order" class="btn btn-accent btn-lg btn-block" style="margin-top: 20px;">
                        <i class="fas fa-lock"></i> Place Order and Pay
                    </button>

                    <div style="text-align: center; margin-top: 14px; font-size: 11px; color: #94a3b8;">
                        By placing your order, you agree to ShopEasy's <a href="terms.php" style="color: #2563eb;">Conditions of Use</a> and <a href="privacy.php" style="color: #2563eb;">Privacy Notice</a>.
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

<script>
function showPaymentForm(type) {
    const cardBox = document.getElementById('cardFieldsBox');
    const upiBox = document.getElementById('upiFieldsBox');
    if (cardBox) cardBox.style.display = (type === 'card') ? 'block' : 'none';
    if (upiBox) upiBox.style.display = (type === 'upi') ? 'block' : 'none';
}

document.addEventListener('DOMContentLoaded', () => {
    const standardFee = <?php echo (float)$cartBreakdown['shipping']; ?>;
    const baseTotalWithoutShipping = <?php echo round(($cartBreakdown['subtotal'] - $cartBreakdown['discount']) + $cartBreakdown['tax'], 2); ?>;
    const expressFee = 9.99;

    const deliveryRadios = document.querySelectorAll('input[name="delivery_option"]');
    const feeEl = document.getElementById('summaryDeliveryFee');
    const totalEl = document.getElementById('summaryFinalTotal');

    deliveryRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            deliveryRadios.forEach(r => r.closest('.payment-option-row')?.classList.remove('selected'));
            this.closest('.payment-option-row')?.classList.add('selected');

            if (this.value === 'express') {
                if (feeEl) feeEl.innerHTML = '$' + expressFee.toFixed(2);
                if (totalEl) totalEl.textContent = '$' + (baseTotalWithoutShipping + expressFee).toFixed(2);
            } else {
                if (feeEl) feeEl.innerHTML = standardFee === 0 ? '<strong style="color: #10b981;">FREE</strong>' : '$' + standardFee.toFixed(2);
                if (totalEl) totalEl.textContent = '$' + (baseTotalWithoutShipping + standardFee).toFixed(2);
            }
        });
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>
