<?php
ob_start(); // Start output buffering for safe redirects
session_start();
include 'config/database.php';
include 'includes/functions.php';

// Prepare user data for header if logged in
$isLoggedIn = isLoggedIn();
$username = $isLoggedIn ? htmlspecialchars($_SESSION['username']) : '';
$cartCount = getCartCount();
$wishlistCount = $isLoggedIn ? getWishlistCount($_SESSION['user_id']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - Frequently Asked Questions</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Custom styles for the FAQ page */
        .faq-section {
            padding: 4rem 0;
            background: #f8f9fa;
            min-height: 80vh;
        }

        .faq-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .faq-header h1 {
            color: #2c3e50;
            margin-bottom: 0.5rem;
            font-size: 3rem;
        }

        .faq-header p {
            color: #666;
            font-size: 1.1rem;
            max-width: 700px;
            margin: 0 auto;
        }
        
        /* Accordion Style */
        .accordion-container {
            max-width: 900px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .accordion-item {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            border: 1px solid #e1e8ed;
        }

        .accordion-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 25px;
            cursor: pointer;
            font-weight: 600;
            color: #2c3e50;
            font-size: 1.1rem;
            transition: background 0.2s;
        }

        .accordion-header:hover {
            background: #f0f8ff;
        }

        .accordion-header i {
            font-size: 1rem;
            color: #3498db;
            transition: transform 0.3s;
        }
        
        .accordion-item.active .accordion-header {
            background: #eaf4ff;
            color: #3498db;
        }

        .accordion-item.active .accordion-header i {
            transform: rotate(180deg);
        }

        .accordion-content {
            padding: 0 25px;
            max-height: 0;
            overflow: hidden;
            /* Simplified transition: only animate max-height */
            transition: max-height 0.5s ease-in-out; 
            border-top: 1px solid #e1e8ed;
        }

        .accordion-item.active .accordion-content {
            /* Use a generous, fixed max-height that ensures all content is visible */
            max-height: 500px; 
            /* Set the final padding instantly—this is key for smooth animation */
            padding: 15px 25px 25px; 
        }
        
        .accordion-content p {
            line-height: 1.6;
            color: #555;
            margin: 0;
        }
    </style>
</head>
<body>
    <!-- Header (Reused from index.php) -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <h1><a href="index.php">ShopEasy</a></h1>
                </div>
                
                <div class="search-bar">
                    <form action="search.php" method="GET">
                        <input type="text" name="query" placeholder="Search products..." required>
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
                
                <div class="header-actions">
                    <div class="user-menu">
                        <?php if ($isLoggedIn): ?>
                            <a href="profile.php" class="user-link">
                                <i class="fas fa-user"></i>
                                <?php echo $username; ?>
                            </a>
                            <a href="logout.php" class="logout-link">Logout</a>
                        <?php else: ?>
                            <a href="login.php" class="login-link">Login</a>
                            <a href="register.php" class="register-link">Register</a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="header-icons">
                        <?php if ($isLoggedIn): ?>
                            <a href="wishlist.php" class="wishlist-link">
                                <i class="fas fa-heart"></i>
                                <span class="wishlist-count"><?php echo $wishlistCount; ?></span>
                            </a>
                        <?php endif; ?>
                        
                        <a href="cart.php" class="cart-link">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-count"><?php echo $cartCount; ?></span>
                        </a>
                    </div>
                </div>
            </div>
            
            <nav class="main-nav">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="products.php">All Products</a></li>
                    <li><a href="products.php?category=electronics">Electronics</a></li>
                    <li><a href="products.php?category=clothing">Clothing</a></li>
                    <li><a href="products.php?category=home">Home & Garden</a></li>
                    <li><a href="products.php?category=sports">Sports</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <div class="container">
            <a href="index.php">Home</a> > 
            <span>FAQ</span>
        </div>
    </div>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <div class="faq-header">
                <h1>Frequently Asked Questions</h1>
                <p>Find answers to common questions about ordering, payments, and shipping with ShopEasy.</p>
            </div>

            <div class="accordion-container">
                
                <!-- Section 1: Ordering -->
                <h2 style="font-size: 1.5rem; color: #2c3e50; margin-top: 2rem;">Ordering & Accounts</h2>

                <div class="accordion-item">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        How do I track my order?
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="accordion-content">
                        <p>You can track your order by logging into your account, navigating to the "My Orders" section, and clicking "View Details" on the specific order. Once the item ships, a tracking number will be provided there.</p>
                    </div>
                </div>

                <div class="accordion-item">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        Can I modify or cancel my order after placing it?
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="accordion-content">
                        <p>We process orders quickly, so modifications or cancellations are only possible if the order has not yet entered the shipping phase. Please contact our support team immediately if you need to make changes.</p>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        What if I forgot my password?
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="accordion-content">
                        <p>Go to the <a href="login.php" style="color: #3498db;">Login page</a> and click on the "Forgot your password?" link. We will send a password reset link to your registered email address.</p>
                    </div>
                </div>

                <!-- Section 2: Shipping & Delivery -->
                <h2 style="font-size: 1.5rem; color: #2c3e50; margin-top: 2rem;">Shipping & Delivery</h2>
                
                <div class="accordion-item">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        How much does shipping cost?
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="accordion-content">
                        <p>Shipping is **FREE** for all orders over $50. For orders under $50, a flat rate of $9.99 is applied. We offer standard shipping only.</p>
                    </div>
                </div>

                <div class="accordion-item">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        Do you ship internationally?
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="accordion-content">
                        <p>Currently, ShopEasy only ships within the United States and Canada. We are working on expanding our delivery coverage soon!</p>
                    </div>
                </div>

                <!-- Section 3: Returns & Refunds -->
                <h2 style="font-size: 1.5rem; color: #2c3e50; margin-top: 2rem;">Returns & Refunds</h2>

                <div class="accordion-item">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        What is your return policy?
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="accordion-content">
                        <p>We offer a 30-day money-back guarantee on most products, starting from the day your order is delivered. Items must be unused and in original packaging. Some exceptions apply (e.g., clearance items).</p>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        How long does a refund take?
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="accordion-content">
                        <p>Once we receive your returned item, your refund will be processed within 5-7 business days. The funds should appear in your original payment method within 3-10 days, depending on your bank.</p>
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

    <script>
        function toggleAccordion(header) {
            const item = header.parentNode;
            const content = header.nextElementSibling;
            
            // Close all other active items
            document.querySelectorAll('.accordion-item.active').forEach(activeItem => {
                if (activeItem !== item) {
                    activeItem.classList.remove('active');
                    activeItem.querySelector('.accordion-content').style.maxHeight = 0;
                }
            });

            // Toggle current item
            if (item.classList.contains('active')) {
                item.classList.remove('active');
                content.style.maxHeight = 0;
            } else {
                item.classList.add('active');
                // Set max-height to scroll height to enable smooth transition
                content.style.maxHeight = content.scrollHeight + "px";
            }
        }
    </script><script>
        function toggleAccordion(header) {
            const item = header.parentNode;
            const content = header.nextElementSibling;
            
            // --- Step 1: Close all other active items (Refined) ---
            document.querySelectorAll('.accordion-item.active').forEach(activeItem => {
                if (activeItem !== item) {
                    activeItem.classList.remove('active');
                    // Reset height and padding for smooth collapse
                    activeItem.querySelector('.accordion-content').style.maxHeight = '0';
                    activeItem.querySelector('.accordion-content').style.paddingTop = '0';
                    activeItem.querySelector('.accordion-content').style.paddingBottom = '0';
                }
            });

            // --- Step 2: Toggle current item (Refined) ---
            if (item.classList.contains('active')) {
                // Collapse
                item.classList.remove('active');
                content.style.maxHeight = '0';
                content.style.paddingTop = '0';
                content.style.paddingBottom = '0';
            } else {
                // Expand
                item.classList.add('active');
                
                // Set initial height based on content
                content.style.maxHeight = content.scrollHeight + 'px';
                
                // Re-apply padding after max-height is set to ensure it's calculated in scrollHeight
                content.style.paddingTop = '15px'; 
                content.style.paddingBottom = '25px';
            }
        }
    </script>
</body>
</html>