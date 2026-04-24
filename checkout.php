<?php
ob_start();
session_start();
include 'config/database.php';
include 'includes/functions.php';

// Redirect if not logged in
if (!isLoggedIn()) {
    redirect('login.php');
}

$cartItems = getCartItems();
$cartTotal = getCartTotal();

if (empty($cartItems)) {
    redirect('cart.php');
}

$user = getUser($_SESSION['user_id']);
$error = '';
$success = '';

// Handle checkout form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shippingAddress = sanitize($_POST['shipping_address']);
    $billingAddress = sanitize($_POST['billing_address']);
    $paymentMethod = sanitize($_POST['payment_method']);
    $couponCode = sanitize($_POST['coupon_code']);
    
    if (empty($shippingAddress) || empty($billingAddress) || empty($paymentMethod)) {
        $error = 'Please fill in all required fields';
    } else {
        // Calculate final total
        $shippingCost = $cartTotal >= 50 ? 0 : 9.99;
        $tax = $cartTotal * 0.08;
        $discount = 0;
        
        // Apply coupon if valid
        if (!empty($couponCode)) {
            $coupon = getCoupon($couponCode);
            if ($coupon && $coupon['status'] === 'active' && $cartTotal >= $coupon['minimum_amount']) {
                if ($coupon['discount_type'] === 'percentage') {
                    $discount = $cartTotal * ($coupon['discount_value'] / 100);
                } else {
                    $discount = $coupon['discount_value'];
                }
            }
        }
        
        $finalTotal = $cartTotal + $shippingCost + $tax - $discount;
        
        // Create order
        $orderId = createOrder(
        $_SESSION['user_id'], 
        $cartItems, 
        $finalTotal, 
        $shippingAddress, 
        $billingAddress, 
        $paymentMethod,
        $cartTotal,      
        $tax,            
        $shippingCost,   
        $discount        
);
        
        if ($orderId) {
            // Clear cart
            $_SESSION['cart'] = [];
            
            // Redirect to order confirmation
            redirect("order-confirmation.php?id=$orderId");
        } else {
            $error = 'Error creating order. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - ShopEasy</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <h1><a href="index.php">ShopEasy</a></h1>
                </div>
                
                <div class="header-actions">
                    <div class="user-menu">
                        <a href="profile.php" class="user-link">
                            <i class="fas fa-user"></i>
                            <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </a>
                        <a href="logout.php" class="logout-link">Logout</a>
                    </div>
                    
                    <div class="cart">
                        <a href="cart.php" class="cart-link">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-count"><?php echo getCartCount(); ?></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <div class="container">
            <a href="index.php">Home</a> > 
            <a href="cart.php">Cart</a> > 
            <span>Checkout</span>
        </div>
    </div>

    <!-- Checkout Section -->
    <section class="checkout-section">
        <div class="container">
            <h1>Checkout</h1>
            
            <?php if ($error): ?>
                <div class="flash-message flash-error">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <div class="checkout-content">
                <div class="checkout-form">
                    <form method="POST" data-validate>
                        <!-- Shipping Information -->
                        <div class="form-section">
                            <h2><i class="fas fa-shipping-fast"></i> Shipping Information</h2>
                            
                            <div class="form-group">
                                <label for="shipping_address">Shipping Address *</label>
                                <textarea id="shipping_address" name="shipping_address" rows="4" required 
                                          placeholder="Enter your complete shipping address"><?php echo isset($_POST['shipping_address']) ? htmlspecialchars($_POST['shipping_address']) : htmlspecialchars($user['address']); ?></textarea>
                            </div>
                        </div>
                        
                        <!-- Billing Information -->
                        <div class="form-section">
                            <h2><i class="fas fa-credit-card"></i> Billing Information</h2>
                            
                            <div class="form-group">
                                <label for="billing_address">Billing Address *</label>
                                <textarea id="billing_address" name="billing_address" rows="4" required 
                                          placeholder="Enter your billing address"><?php echo isset($_POST['billing_address']) ? htmlspecialchars($_POST['billing_address']) : htmlspecialchars($user['address']); ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="payment_method">Payment Method *</label>
                                <select id="payment_method" name="payment_method" required>
                                    <option value="">Select Payment Method</option>
                                    <option value="credit_card" <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] === 'credit_card') ? 'selected' : ''; ?>>Credit Card</option>
                                    <option value="debit_card" <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] === 'debit_card') ? 'selected' : ''; ?>>Debit Card</option>
                                    <option value="paypal" <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] === 'paypal') ? 'selected' : ''; ?>>PayPal</option>
                                    <option value="bank_transfer" <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] === 'bank_transfer') ? 'selected' : ''; ?>>Bank Transfer</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Coupon Code -->
                        <div class="form-section">
                            <h2><i class="fas fa-tag"></i> Coupon Code</h2>
                            
                            <div class="form-group">
                                <label for="coupon_code">Coupon Code</label>
                                <div class="coupon-input">
                                    <input type="text" id="coupon_code" name="coupon_code" 
                                           value="<?php echo isset($_POST['coupon_code']) ? htmlspecialchars($_POST['coupon_code']) : ''; ?>"
                                           placeholder="Enter coupon code">
                                    <button type="button" onclick="applyCoupon()" class="btn btn-outline">Apply</button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="checkout-actions">
                            <a href="cart.php" class="btn btn-outline">
                                <i class="fas fa-arrow-left"></i>
                                Back to Cart
                            </a>
                            
                            <button type="submit" class="btn btn-primary btn-large">
                                <i class="fas fa-credit-card"></i>
                                Place Order
                            </button>
                        </div>
                    </form>
                </div>
                
                <div class="order-summary">
                    <h2>Order Summary</h2>
                    
                    <div class="order-items">
                        <?php foreach ($cartItems as $item): ?>
                            <div class="order-item">
                                <img src="<?php echo $item['product']['image']; ?>" alt="<?php echo htmlspecialchars($item['product']['name']); ?>">
                                <div class="item-details">
                                    <h4><?php echo htmlspecialchars($item['product']['name']); ?></h4>
                                    <p>Quantity: <?php echo $item['quantity']; ?></p>
                                    <p class="item-price">$<?php echo number_format($item['product']['price'] * $item['quantity'], 2); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="order-totals">
                        <div class="total-row">
                            <span>Subtotal:</span>
                            <span>$<?php echo number_format($cartTotal, 2); ?></span>
                        </div>
                        
                        <div class="total-row">
                            <span>Shipping:</span>
                            <span><?php echo $cartTotal >= 50 ? 'FREE' : '$9.99'; ?></span>
                        </div>
                        
                        <div class="total-row">
                            <span>Tax:</span>
                            <span>$<?php echo number_format($cartTotal * 0.08, 2); ?></span>
                        </div>
                        
                        <div class="total-row total">
                            <span>Total:</span>
                            <span>$<?php echo number_format($cartTotal + ($cartTotal >= 50 ? 0 : 9.99) + ($cartTotal * 0.08), 2); ?></span>
                        </div>
                    </div>
                    
                    <div class="security-info">
                        <h3><i class="fas fa-shield-alt"></i> Secure Checkout</h3>
                        <ul>
                            <li><i class="fas fa-lock"></i> SSL Encrypted</li>
                            <li><i class="fas fa-credit-card"></i> Secure Payment</li>
                            <li><i class="fas fa-undo"></i> 30-Day Returns</li>
                            <li><i class="fas fa-headset"></i> 24/7 Support</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>ShopEasy</h3>
                    <p>Your trusted online shopping destination for quality products at great prices.</p>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="products.php">All Products</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <li><a href="faq.php">FAQ</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Customer Service</h4>
                    <ul>
                        <li><a href="shipping.php">Shipping Info</a></li>
                        <li><a href="returns.php">Returns</a></li>
                        <li><a href="privacy.php">Privacy Policy</a></li>
                        <li><a href="terms.php">Terms of Service</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Connect With Us</h4>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 ShopEasy. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="assets/js/script.js"></script>
    <script>
        function applyCoupon() {
            const couponCode = document.getElementById('coupon_code').value;
            if (couponCode) {
                // In a real application, you would validate the coupon via AJAX
                showNotification('Coupon applied successfully!', 'success');
            }
        }
    </script>
</body>
</html>




