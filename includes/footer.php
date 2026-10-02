    <!-- Trust Proposition Guarantee Strip -->
    <section class="trust-guarantee-strip">
        <div class="container">
            <div class="trust-grid">
                <div class="trust-card">
                    <div class="trust-icon-box">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <div class="trust-text">
                        <h4>Free & Fast Delivery</h4>
                        <p>On all orders above $50. Tracked in real time.</p>
                    </div>
                </div>
                <div class="trust-card">
                    <div class="trust-icon-box">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                    <div class="trust-text">
                        <h4>7-Day Easy Returns</h4>
                        <p>No questions asked, hassle-free replacement.</p>
                    </div>
                </div>
                <div class="trust-card">
                    <div class="trust-icon-box">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="trust-text">
                        <h4>100% Genuine Products</h4>
                        <p>Direct from verified brands with full warranty.</p>
                    </div>
                </div>
                <div class="trust-card">
                    <div class="trust-icon-box">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="trust-text">
                        <h4>Secure Payments</h4>
                        <p>256-bit SSL encrypted checkout & COD support.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="footer-top">
            <div class="container">
                <div class="footer-grid">
                    <!-- Column 1: Brand Info -->
                    <div class="footer-col brand-col">
                        <div class="footer-brand">
                            <div class="logo-icon-box">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <span class="logo-name">Shop<span>Easy</span></span>
                        </div>
                        <p class="brand-desc">
                            ShopEasy is India's leading online shopping destination, offering high quality electronics, fashion, lifestyle essentials, and home appliances with best price guarantees.
                        </p>
                        <div class="footer-social-icons">
                            <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                            <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                            <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                            <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>

                    <!-- Column 2: Quick Links -->
                    <div class="footer-col">
                        <h4 class="footer-heading">Quick Links</h4>
                        <ul class="footer-links">
                            <li><a href="index.php">Home</a></li>
                            <li><a href="products.php">All Products</a></li>
                            <li><a href="products.php?on_sale=1">Today's Deals</a></li>
                            <li><a href="about.php">About Us</a></li>
                            <li><a href="contact.php">Contact Us</a></li>
                            <li><a href="faq.php">Help & FAQs</a></li>
                        </ul>
                    </div>

                    <!-- Column 3: Customer Care -->
                    <div class="footer-col">
                        <h4 class="footer-heading">Customer Care</h4>
                        <ul class="footer-links">
                            <li><a href="shipping.php">Shipping & Delivery</a></li>
                            <li><a href="returns.php">Returns & Refunds</a></li>
                            <li><a href="privacy.php">Privacy Policy</a></li>
                            <li><a href="terms.php">Terms of Service</a></li>
                            <li><a href="profile.php?tab=orders">Order Tracking</a></li>
                            <li><a href="contact.php">Report an Issue</a></li>
                        </ul>
                    </div>

                    <!-- Column 4: Newsletter -->
                    <div class="footer-col newsletter-col">
                        <h4 class="footer-heading">Stay in the Loop</h4>
                        <p>Subscribe for exclusive coupons, flash sale alerts, and VIP discounts.</p>
                        <form class="footer-newsletter-form" onsubmit="event.preventDefault(); showToast('Thank you for subscribing! Your 10% coupon code is WELCOME10.', 'success'); this.reset();">
                            <div class="newsletter-input-group">
                                <input type="email" placeholder="Enter your email address..." required>
                                <button type="submit" aria-label="Subscribe to newsletter"><i class="fas fa-paper-plane"></i></button>
                            </div>
                        </form>
                        <div class="download-app-badges">
                            <span class="app-text"><i class="fas fa-mobile-alt"></i> Available on iOS & Android</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="container footer-bottom-inner">
                <p class="copyright-text">
                    &copy; <?php echo date('Y'); ?> <strong>ShopEasy</strong> Inc. All rights reserved. Built with precision and care.
                </p>
                <div class="payment-methods">
                    <span class="payment-badge"><i class="fab fa-cc-visa"></i> Visa</span>
                    <span class="payment-badge"><i class="fab fa-cc-mastercard"></i> Mastercard</span>
                    <span class="payment-badge"><i class="fab fa-cc-paypal"></i> PayPal</span>
                    <span class="payment-badge"><i class="fab fa-apple-pay"></i> Apple Pay</span>
                    <span class="payment-badge"><i class="fas fa-money-bill-wave"></i> Cash on Delivery</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Bottom Navigation Bar (Flipkart / Meesho App Experience) -->
    <nav class="mobile-bottom-nav">
        <a href="index.php" class="bottom-nav-item <?php echo ($activeNav ?? '') === 'home' ? 'active' : ''; ?>">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="products.php" class="bottom-nav-item <?php echo ($activeNav ?? '') === 'products' ? 'active' : ''; ?>">
            <i class="fas fa-th-large"></i>
            <span>Categories</span>
        </a>
        <a href="products.php?on_sale=1" class="bottom-nav-item">
            <i class="fas fa-fire"></i>
            <span>Deals</span>
        </a>
        <a href="<?php echo isLoggedIn() ? 'wishlist.php' : 'login.php'; ?>" class="bottom-nav-item <?php echo ($activeNav ?? '') === 'wishlist' ? 'active' : ''; ?>">
            <div class="bottom-icon-badge">
                <i class="fas fa-heart"></i>
                <span class="bottom-badge" id="bottomWishlistBadge"><?php echo $wishlistCount ?? 0; ?></span>
            </div>
            <span>Wishlist</span>
        </a>
        <a href="cart.php" class="bottom-nav-item <?php echo ($activeNav ?? '') === 'cart' ? 'active' : ''; ?>">
            <div class="bottom-icon-badge">
                <i class="fas fa-shopping-cart"></i>
                <span class="bottom-badge" id="bottomCartBadge"><?php echo $cartCount ?? 0; ?></span>
            </div>
            <span>Cart</span>
        </a>
        <a href="<?php echo isLoggedIn() ? 'profile.php' : 'login.php'; ?>" class="bottom-nav-item <?php echo ($activeNav ?? '') === 'profile' ? 'active' : ''; ?>">
            <i class="fas fa-user"></i>
            <span>Account</span>
        </a>
    </nav>

    <!-- Main JavaScript Bundle -->
    <script src="assets/js/script.js?v=<?php echo filemtime(__DIR__ . '/../assets/js/script.js'); ?>"></script>
    <script src="assets/js/app.js?v=<?php echo filemtime(__DIR__ . '/../assets/js/app.js'); ?>"></script>
</body>
</html>
