<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$cartBreakdown = getCartBreakdown();
$pageTitle = 'Shopping Cart (' . $cartBreakdown['item_count'] . ' items) - ShopEasy';
$activeNav = 'cart';

require_once 'includes/header.php';
?>

<main class="cart-page-container" style="padding: 24px 0 50px;">
    <div class="container">
        <!-- Breadcrumbs -->
        <nav class="catalog-breadcrumb" aria-label="breadcrumb">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <i class="fas fa-chevron-right"></i>
            <span>Shopping Cart</span>
        </nav>

        <h1 style="font-family: 'Poppins', sans-serif; font-size: 24px; font-weight: 700; color: #0f172a; margin-bottom: 20px;">
            Shopping Cart <span style="font-size: 16px; color: #64748b; font-weight: 500;">(<?php echo $cartBreakdown['item_count']; ?> items)</span>
        </h1>

        <?php if (empty($cartBreakdown['items'])): ?>
            <!-- Empty Cart State -->
            <div style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 60px 20px; text-align: center; max-width: 680px; margin: 20px auto;">
                <div style="width: 80px; height: 80px; background: #eff6ff; color: #2563eb; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 36px; margin: 0 auto 18px;">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <h2 style="font-size: 22px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">Your Shopping Cart is Empty</h2>
                <p style="color: #64748b; font-size: 14px; margin-bottom: 24px;">Explore our best deals, discover trending gadgets, and add items to your cart.</p>
                <div style="display: flex; gap: 12px; justify-content: center;">
                    <a href="products.php" class="btn btn-primary"><i class="fas fa-th-large"></i> Continue Shopping</a>
                    <a href="products.php?on_sale=1" class="btn btn-accent"><i class="fas fa-fire"></i> Today's Deals</a>
                </div>
            </div>
        <?php else: ?>
            <div class="cart-split-layout">
                <!-- Left Column: Cart Items -->
                <div class="cart-items-card">
                    <div class="cart-card-header">
                        <span style="font-weight: 700; font-size: 16px; color: #0f172a;">Items in Cart</span>
                        <span style="font-size: 13px; color: #64748b;">Price</span>
                    </div>

                    <div class="cart-items-list">
                        <?php foreach ($cartBreakdown['items'] as $item): ?>
                        <?php 
                            $p = $item['product'];
                            $pImg = getProductImageUrl($p['image']);
                        ?>
                        <div class="cart-item-row" id="cartRow_<?php echo $p['id']; ?>">
                            <!-- Thumbnail -->
                            <div class="cart-item-thumb">
                                <a href="product.php?id=<?php echo $p['id']; ?>">
                                    <img src="<?php echo $pImg; ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
                                </a>
                            </div>

                            <!-- Details -->
                            <div class="cart-item-info">
                                <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #2563eb;">
                                    <?php echo htmlspecialchars($p['category_name'] ?? 'ShopEasy'); ?>
                                </span>
                                <h3 class="cart-item-title">
                                    <a href="product.php?id=<?php echo $p['id']; ?>"><?php echo htmlspecialchars($p['name']); ?></a>
                                </h3>

                                <div style="font-size: 12px; color: <?php echo $p['stock_quantity'] > 0 ? '#10b981' : '#ef4444'; ?>; font-weight: 600;">
                                    <?php echo $p['stock_quantity'] > 0 ? 'In Stock &bull; Eligible for Free Shipping' : 'Currently Out of Stock'; ?>
                                </div>

                                <!-- Actions & Quantity Stepper -->
                                <div class="cart-item-actions">
                                    <div class="qty-stepper" style="height: 34px;">
                                        <button type="button" class="qty-step-btn cart-qty-minus" data-product-id="<?php echo $p['id']; ?>">-</button>
                                        <input type="text" id="cartQty_<?php echo $p['id']; ?>" class="qty-input" value="<?php echo $item['quantity']; ?>" readonly style="width: 36px; font-size: 13px;">
                                        <button type="button" class="qty-step-btn cart-qty-plus" data-product-id="<?php echo $p['id']; ?>">+</button>
                                    </div>

                                    <span class="cart-action-link" onclick="saveForLaterAjax(<?php echo $p['id']; ?>);">
                                        <i class="far fa-heart"></i> Save for Later
                                    </span>
                                    <span style="color: #cbd5e1;">|</span>
                                    <span class="cart-action-link delete-link" onclick="removeCartItemAjax(<?php echo $p['id']; ?>)">
                                        <i class="far fa-trash-alt"></i> Delete
                                    </span>
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="cart-item-price-col">
                                <div class="cart-item-total-price">$<?php echo number_format($item['subtotal'], 2); ?></div>
                                <?php if ($item['quantity'] > 1): ?>
                                    <div class="cart-item-unit-price">($<?php echo number_format($item['unit_price'], 2); ?> each)</div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 20px;">
                        <a href="products.php" class="btn btn-outline btn-sm">
                            <i class="fas fa-arrow-left"></i> Add More Products
                        </a>
                        <button type="button" class="btn btn-outline btn-sm" onclick="clearCartAjax()">
                            Clear Entire Cart
                        </button>
                    </div>
                </div>

                <!-- Right Column: Order Summary Sidebar -->
                <div class="cart-summary-card">
                    <!-- Free Delivery Progress Bar (Amazon Pattern) -->
                    <div class="free-shipping-progress-box">
                        <?php if ($cartBreakdown['is_free_shipping']): ?>
                            <div class="free-shipping-msg">
                                <i class="fas fa-check-circle" style="color: #10b981;"></i> Your order qualifies for <strong>FREE Delivery!</strong>
                            </div>
                            <div class="progress-bar-bg" style="height: 6px; background: #a7f3d0;">
                                <div class="progress-bar-fill" style="width: 100%; background: #10b981;"></div>
                            </div>
                        <?php else: ?>
                            <div class="free-shipping-msg">
                                <i class="fas fa-truck"></i> Add <strong>$<?php echo number_format($cartBreakdown['amount_needed_for_free_shipping'], 2); ?></strong> more to get <strong>FREE Delivery!</strong>
                            </div>
                            <?php 
                                $pct = min(100, round(($cartBreakdown['subtotal'] / $cartBreakdown['free_shipping_threshold']) * 100));
                            ?>
                            <div class="progress-bar-bg" style="height: 6px;">
                                <div class="progress-bar-fill" style="width: <?php echo $pct; ?>%; background: #2563eb;"></div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 16px;">Price Details</h3>

                    <!-- Coupon Applicator Input -->
                    <div class="coupon-box-wrapper">
                        <?php if (!empty($cartBreakdown['applied_coupon'])): ?>
                            <div class="applied-coupon-pill">
                                <div>
                                    <i class="fas fa-tag"></i> <strong><?php echo htmlspecialchars($cartBreakdown['applied_coupon']['code']); ?></strong> applied!
                                </div>
                                <button type="button" onclick="removeCouponAjax()" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 12px; font-weight: 700;">
                                    Remove
                                </button>
                            </div>
                        <?php else: ?>
                            <div class="coupon-applicator-group">
                                <input type="text" id="couponCodeInput" placeholder="Enter Promo Code" maxlength="15">
                                <button type="button" class="btn btn-primary btn-sm" onclick="applyCouponAjax()">Apply</button>
                            </div>
                            <div style="font-size: 11px; color: #64748b; margin-top: -10px; margin-bottom: 16px;">
                                Try: <strong style="color: #2563eb; cursor: pointer;" onclick="document.getElementById('couponCodeInput').value='WELCOME10'; applyCouponAjax();">WELCOME10</strong> (10% OFF) or <strong style="color: #2563eb; cursor: pointer;" onclick="document.getElementById('couponCodeInput').value='SAVE50'; applyCouponAjax();">SAVE50</strong>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Price Calculations Breakdown -->
                    <div class="summary-line-item">
                        <span>Price (<?php echo $cartBreakdown['item_count']; ?> items):</span>
                        <span>$<?php echo number_format($cartBreakdown['mrp_total'], 2); ?></span>
                    </div>

                    <?php if ($cartBreakdown['mrp_total'] > $cartBreakdown['subtotal']): ?>
                    <div class="summary-line-item discount-item">
                        <span>Store Catalog Discount:</span>
                        <span>-$<?php echo number_format($cartBreakdown['mrp_total'] - $cartBreakdown['subtotal'], 2); ?></span>
                    </div>
                    <?php endif; ?>

                    <?php if ($cartBreakdown['discount'] > 0): ?>
                    <div class="summary-line-item discount-item">
                        <span>Coupon Discount:</span>
                        <span>-$<?php echo number_format($cartBreakdown['discount'], 2); ?></span>
                    </div>
                    <?php endif; ?>

                    <div class="summary-line-item">
                        <span>Estimated Tax (5%):</span>
                        <span>$<?php echo number_format($cartBreakdown['tax'], 2); ?></span>
                    </div>

                    <div class="summary-line-item">
                        <span>Delivery Charges:</span>
                        <span>
                            <?php echo $cartBreakdown['shipping'] == 0 ? '<strong style="color: #10b981;">FREE</strong>' : '$'.number_format($cartBreakdown['shipping'], 2); ?>
                        </span>
                    </div>

                    <div class="summary-line-item summary-total-line">
                        <span>Total Amount:</span>
                        <span>$<?php echo number_format($cartBreakdown['total'], 2); ?></span>
                    </div>

                    <?php if ($cartBreakdown['total_savings'] > 0): ?>
                        <div class="savings-banner-green">
                            <i class="fas fa-piggy-bank"></i> You will save <strong>$<?php echo number_format($cartBreakdown['total_savings'], 2); ?></strong> on this order!
                        </div>
                    <?php endif; ?>

                    <!-- Proceed to Checkout Button -->
                    <a href="checkout.php" class="btn btn-accent btn-lg btn-block" style="margin-top: 14px;">
                        Proceed to Checkout <i class="fas fa-arrow-right"></i>
                    </a>

                    <!-- Security Strip -->
                    <div style="text-align: center; margin-top: 16px; font-size: 11px; color: #94a3b8; display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <i class="fas fa-shield-alt" style="color: #10b981;"></i> Safe and Secure Payments. 100% Authentic products.
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
