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
    <title>Privacy Policy - ShopEasy</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Custom styles for the Policy page */
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
            color: #3498db;
            margin-top: 0;
            margin-bottom: 1.5rem;
            font-size: 2rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .policy-block h3 {
            color: #34495e;
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
            <span>Privacy Policy</span>
        </div>
    </div>

    <!-- Privacy Policy Section -->
    <section class="policy-section">
        <div class="container">
            <div class="policy-header">
                <h1>Privacy Policy</h1>
                <p>Your privacy is of utmost importance to us. This policy outlines how ShopEasy collects, uses, maintains, and discloses information collected from users of the website.</p>
            </div>
            
            <p class="date-info">Effective Date: October 30, 2024</p>

            <div class="content-container">

                <div class="policy-block">
                    <h2><i class="fas fa-user-shield"></i> Information We Collect</h2>
                    <p>We collect personal identification information from Users in a variety of ways, including, but not limited to, when Users visit our site, register on the site, place an order, fill out a form, and in connection with other activities, services, features, or resources we make available on our Site.</p>

                    <h3>Personal Identification Information:</h3>
                    <ul>
                        <li>**Account Data:** Name, username, email address, hashed password, phone number.</li>
                        <li>**Transaction Data:** Shipping address, billing address, and payment method details (though we do not store full credit card numbers).</li>
                        <li>**Usage Data:** Details about products viewed, items added to cart/wishlist, and order history.</li>
                    </ul>
                </div>

                <div class="policy-block">
                    <h2><i class="fas fa-cogs"></i> How We Use Collected Information</h2>
                    <p>ShopEasy collects and uses Users' personal information for the following purposes:</p>
                    <ol>
                        <li>To **Process Transactions:** To process payments and fulfill orders you have placed, including shipping products and providing order confirmations.</li>
                        <li>To **Personalize User Experience:** We may use information in the aggregate to understand how our Users as a group use the services and resources provided on our Site.</li>
                        <li>To **Improve Our Site:** We continually strive to improve our website offerings based on the information and feedback we receive from you.</li>
                        <li>To **Send Periodic Emails:** The email address Users provide for order processing will only be used to send them information and updates pertaining to their order. It may also be used to respond to their inquiries, and/or other requests or questions.</li>
                    </ol>
                </div>

                <div class="policy-block">
                    <h2><i class="fas fa-lock"></i> How We Protect Your Information</h2>
                    <p>We adopt appropriate data collection, storage, and processing practices and security measures to protect against unauthorized access, alteration, disclosure, or destruction of your personal information, username, password, transaction information, and data stored on our Site.</p>
                    <ul>
                        <li>**Encryption:** All sensitive data (like passwords) is stored in a hashed format in our database.</li>
                        <li>**SSL:** Our Site is secured using SSL certificates to ensure data transmission between your browser and our server is protected.</li>
                    </ul>
                </div>

                <div class="policy-block">
                    <h2><i class="fas fa-share-alt"></i> Sharing Your Personal Information</h2>
                    <p>We do **not** sell, trade, or rent Users' personal identification information to others. We may share generic aggregated demographic information not linked to any personal identification information regarding visitors and users with our business partners, trusted affiliates, and advertisers for the purposes outlined above.</p>
                    <p>We may share your data with third-party service providers (like shipping carriers and payment gateways) only to the extent necessary to fulfill your order and provide our services.</p>
                </div>

                <div class="policy-block">
                    <h2><i class="fas fa-cookie-bite"></i> Web Browser Cookies</h2>
                    <p>Our Site may use "cookies" to enhance the User experience. Your web browser places cookies on your hard drive for record-keeping purposes and sometimes to track information about them. You may choose to set your web browser to refuse cookies, or to alert you when cookies are being sent. If they do so, note that some parts of the Site may not function properly.</p>
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