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
    <title>Terms of Service - ShopEasy</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Custom styles for the Policy page, reusing structure from privacy.php */
        .policy-section {
            padding: 4rem 0;
            background: white;
            min-height: 80vh;
        }

        .policy-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .policy-header h1 {
            color: #2c3e50;
            margin-bottom: 0.5rem;
            font-size: 3rem;
        }

        .policy-header p {
            color: #666;
            font-size: 1.2rem;
            max-width: 800px;
            margin: 0 auto;
        }

        .content-container {
            max-width: 1000px;
            margin: 0 auto;
            background: #f8f9fa;
            padding: 3rem;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .policy-block {
            margin-bottom: 3rem;
            padding-bottom: 1rem;
            border-bottom: 1px dashed #e1e8ed;
        }

        .policy-block:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .policy-block h2 {
            color: #34495e; /* Neutral color for terms */
            margin-top: 0;
            margin-bottom: 1.5rem;
            font-size: 2rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .policy-block h3 {
            color: #3498db;
            margin-top: 2rem;
            margin-bottom: 0.8rem;
            font-size: 1.5rem;
        }

        .policy-block p, .policy-block ul, .policy-block ol {
            line-height: 1.7;
            color: #555;
            font-size: 1rem;
        }

        .policy-block ul, .policy-block ol {
            margin-left: 25px;
            margin-bottom: 1rem;
        }
        
        .date-info {
            font-size: 0.9rem;
            font-style: italic;
            color: #666;
            text-align: right;
            margin-top: -2rem;
            margin-bottom: 2rem;
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
            <span>Terms of Service</span>
        </div>
    </div>

    <!-- Terms of Service Section -->
    <section class="policy-section">
        <div class="container">
            <div class="policy-header">
                <h1>Terms of Service</h1>
                <p>Please read these Terms of Service carefully before using the ShopEasy website. By accessing or using the site, you agree to be bound by these terms.</p>
            </div>
            
            <p class="date-info">Last Updated: October 30, 2024</p>

            <div class="content-container">

                <div class="policy-block">
                    <h2><i class="fas fa-file-contract"></i> Agreement to Terms</h2>
                    <p>These Terms apply to all visitors, users, and others who access or use the Service. If you disagree with any part of the terms, then you may not access the Service.</p>
                    <p>You must be at least 18 years of age to use this website. By using this website and by agreeing to these terms and conditions, you warrant and represent that you are at least 18 years of age.</p>
                </div>

                <div class="policy-block">
                    <h2><i class="fas fa-user-check"></i> User Accounts and Responsibilities</h2>

                    <h3>Account Creation</h3>
                    <p>When you create an account with us, you must provide information that is accurate, complete, and current at all times. Failure to do so constitutes a breach of the Terms, which may result in immediate termination of your account.</p>

                    <h3>Security</h3>
                    <p>You are responsible for safeguarding the password that you use to access the Service and for any activities or actions under your password. ShopEasy cannot and will not be liable for any loss or damage arising from your failure to comply with this security obligation.</p>
                </div>

                <div class="policy-block">
                    <h2><i class="fas fa-shopping-bag"></i> Purchases and Payment</h2>
                    <p>If you wish to purchase any product or service made available through the Service ("Purchase"), you may be asked to supply certain information relevant to your Purchase including, without limitation, your credit card number, the expiration date of your credit card, and your billing and shipping addresses.</p>

                    <h3>Product Availability and Pricing</h3>
                    <p>We reserve the right to limit the quantities of any products or services that we offer. All descriptions of products or product pricing are subject to change at any time without notice, at our sole discretion.</p>
                </div>

                <div class="policy-block">
                    <h2><i class="fas fa-copyright"></i> Intellectual Property</h2>
                    <p>The Service and its original content (excluding content provided by users), features, and functionality are and will remain the exclusive property of ShopEasy and its licensors. The Service is protected by copyright, trademark, and other laws of both the United States and foreign countries.</p>
                </div>
                
                <div class="policy-block">
                    <h2><i class="fas fa-ban"></i> Limitation of Liability</h2>
                    <p>In no event shall ShopEasy, nor its directors, employees, partners, agents, suppliers, or affiliates, be liable for any indirect, incidental, special, consequential, or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses, resulting from:</p>
                    <ul>
                        <li>Your access to or use of or inability to access or use the Service;</li>
                        <li>Any unauthorized access to or use of our servers and/or any and all personal information stored therein;</li>
                        <li>Any conduct or content of any third party on the Service.</li>
                    </ul>
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
</body>
</html>