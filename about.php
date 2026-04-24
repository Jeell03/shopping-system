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
    <title>About Us - ShopEasy</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Custom styles for the About Us page */
        .about-section {
            padding: 4rem 0;
            background: white;
        }

        .about-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .about-header h1 {
            color: #2c3e50;
            margin-bottom: 0.5rem;
            font-size: 3rem;
        }

        .about-header p {
            color: #666;
            font-size: 1.2rem;
            max-width: 800px;
            margin: 0 auto;
        }

        .story-section {
            display: flex;
            align-items: center;
            gap: 4rem;
            margin-bottom: 4rem;
            padding: 2rem;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .story-content {
            flex: 1;
        }

        .story-image {
            flex: 1;
            max-width: 50%;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .story-image img {
            width: 100%;
            height: auto;
            display: block;
        }
        
        .story-content h2 {
            color: #3498db;
            margin-bottom: 1rem;
            font-size: 2rem;
        }

        .story-content p {
            line-height: 1.7;
            margin-bottom: 1rem;
            color: #555;
        }

        .mission-values {
            text-align: center;
            margin-bottom: 4rem;
        }

        .mission-values h2 {
            color: #2c3e50;
            margin-bottom: 2rem;
            font-size: 2.5rem;
        }

        .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .value-card {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            text-align: center;
            border-top: 5px solid #3498db;
        }

        .value-card i {
            font-size: 2.5rem;
            color: #3498db;
            margin-bottom: 1rem;
        }

        .value-card h3 {
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        
        /* Responsive Design */
        @media (max-width: 992px) {
            .story-section {
                flex-direction: column;
                gap: 2rem;
                padding: 1.5rem;
            }
            .story-image {
                max-width: 100%;
                order: -1; /* Image appears first on mobile */
            }
            .values-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
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
    
    <div class="breadcrumb">
        <div class="container">
            <a href="index.php">Home</a> > 
            <span>About Us</span>
        </div>
    </div>

    <section class="about-section">
        <div class="container">
            <div class="about-header">
                <h1>Our Story</h1>
                <p>ShopEasy was founded on the principle of making high-quality products accessible to everyone, everywhere, with ease and trust.</p>
            </div>

            <div class="story-section">
                <div class="story-content">
                    <h2>The Genesis of ShopEasy</h2>
                    <p>It all began in 2020 with a simple idea: cut out the complexity of online shopping. We started small, focusing on hand-selecting durable and innovative products in the Electronics and Home categories. Since then, we've grown into a trusted marketplace, but our commitment to a simple, enjoyable shopping experience remains our north star.</p>
                    <p>We believe that finding exactly what you need shouldn't require navigating endless menus or worrying about authenticity. Every product you see here is backed by our promise of quality and value.</p>
                    <a href="contact.php" class="btn btn-primary">Contact Our Team</a>
                </div>
                <div class="story-image">
                    <img src="Image/about-story-office.jpg" alt="A modern, collaborative office setting">
                </div>
            </div>

            <div class="mission-values">
                <h2>Our Mission & Core Values</h2>
                <div class="values-grid">
                    <div class="value-card">
                        <i class="fas fa-hands-helping"></i>
                        <h3>Customer Trust</h3>
                        <p>We prioritize transparency, security, and exceptional support to earn your trust with every transaction.</p>
                    </div>
                    <div class="value-card">
                        <i class="fas fa-box-open"></i>
                        <h3>Quality Products</h3>
                        <p>We meticulously select our inventory, ensuring every item meets a high standard of durability and innovation.</p>
                    </div>
                    <div class="value-card">
                        <i class="fas fa-leaf"></i>
                        <h3>Sustainability</h3>
                        <p>We are dedicated to sustainable practices, from our packaging choices to supporting eco-friendly brands.</p>
                    </div>
                </div>
            </div>
            
        </div>
    </section>

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