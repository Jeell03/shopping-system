<?php
ob_start();
session_start();
include 'config/database.php';
include 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopEasy - Your Online Shopping Destination</title>
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
                
                <div class="search-bar">
                    <form action="search.php" method="GET">
                        <input type="text" name="query" placeholder="Search products..." required>
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
                
                <div class="header-actions">
                    <div class="user-menu">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="profile.php" class="user-link">
                                <i class="fas fa-user"></i>
                                <?php echo htmlspecialchars($_SESSION['username']); ?>
                            </a>
                            <a href="logout.php" class="logout-link">Logout</a>
                        <?php else: ?>
                            <a href="login.php" class="login-link">Login</a>
                            <a href="register.php" class="register-link">Register</a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="header-icons">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="wishlist.php" class="wishlist-link">
                                <i class="fas fa-heart"></i>
                                <span class="wishlist-count"><?php echo getWishlistCount($_SESSION['user_id']); ?></span>
                            </a>
                        <?php endif; ?>
                        
                        <a href="cart.php" class="cart-link">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-count"><?php echo getCartCount(); ?></span>
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

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h2>Welcome to ShopEasy</h2>
                <p>Discover amazing products at unbeatable prices</p>
                <a href="products.php" class="btn btn-primary">Shop Now</a>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="featured-products">
        <div class="container">
            <h2>Featured Products</h2>
            <div class="products-grid">
                <!-- iPhone 15 Pro -->
                <div class="product-card">
                    <div class="product-image">
                        <img src="Image/iPhone 15 Pro.webp" 
                             alt="iPhone 15 Pro">
                        <div class="product-overlay">
                            <a href="product.php?id=1" class="btn btn-outline">View Details</a>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3>iPhone 15 Pro</h3>
                        <p class="product-price">$899.00</p>
                        <button class="btn btn-primary add-to-cart" data-product-id="1">
                            Add to Cart
                        </button>
                    </div>
                </div>

                <!-- Samsung Galaxy S24 -->
                <div class="product-card">
                    <div class="product-image">
                        <img src="Image/Samsung Galaxy S24.webp" 
                             alt="Samsung Galaxy S24">
                        <div class="product-overlay">
                            <a href="product.php?id=2" class="btn btn-outline">View Details</a>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3>Samsung Galaxy S24</h3>
                        <p class="product-price">$799.00</p>
                        <button class="btn btn-primary add-to-cart" data-product-id="2">
                            Add to Cart
                        </button>
                    </div>
                </div>

                <!-- MacBook Air M3 -->
                <div class="product-card">
                    <div class="product-image">
                        <img src="Image/MacBook Air M3.webp" 
                             alt="MacBook Air M3">
                        <div class="product-overlay">
                            <a href="product.php?id=3" class="btn btn-outline">View Details</a>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3>MacBook Air M3</h3>
                        <p class="product-price">$1,199.00</p>
                        <button class="btn btn-primary add-to-cart" data-product-id="3">
                            Add to Cart
                        </button>
                    </div>
                </div>

                <!-- Nike Air Max 270 -->
                <div class="product-card">
                    <div class="product-image">
                        <img src="Image/Nike Air Max 270.webp" 
                             alt="Nike Air Max 270">
                        <div class="product-overlay">
                            <a href="product.php?id=4" class="btn btn-outline">View Details</a>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3>Nike Air Max 270</h3>
                        <p class="product-price">$120.00</p>
                        <button class="btn btn-primary add-to-cart" data-product-id="4">
                            Add to Cart
                        </button>
                    </div>
                </div>

                <!-- Adidas Ultraboost 22 -->
                <div class="product-card">
                    <div class="product-image">
                        <img src="Image/Adidas Ultraboost 22.webp" 
                             alt="Adidas Ultraboost 22">
                        <div class="product-overlay">
                            <a href="product.php?id=5" class="btn btn-outline">View Details</a>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3>Adidas Ultraboost 22</h3>
                        <p class="product-price">$180.00</p>
                        <button class="btn btn-primary add-to-cart" data-product-id="5">
                            Add to Cart
                        </button>
                    </div>
                </div>

                <!-- Dyson V15 Detect -->
                <div class="product-card">
                    <div class="product-image">
                        <img src="Image/Dyson V15 Detect.webp" 
                             alt="Dyson V15 Detect">
                        <div class="product-overlay">
                            <a href="product.php?id=7" class="btn btn-outline">View Details</a>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3>Dyson V15 Detect</h3>
                        <p class="product-price">$649.00</p>
                        <button class="btn btn-primary add-to-cart" data-product-id="7">
                            Add to Cart
                        </button>
                    </div>
                </div>

                <!-- Levi's 501 Jeans -->
                <div class="product-card">
                    <div class="product-image">
                        <img src="Image/Levi's 501 Jeans.webp" 
                             alt="Levi's 501 Jeans">
                        <div class="product-overlay">
                            <a href="product.php?id=8" class="btn btn-outline">View Details</a>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3>Levi's 501 Jeans</h3>
                        <p class="product-price">$799.00</p>
                        <button class="btn btn-primary add-to-cart" data-product-id="8">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories -->
    <section class="categories">
        <div class="container">
            <h2>Shop by Category</h2>
            <div class="categories-grid">
                <div class="category-card">
                    <img src="Image/Electronics.jpg" alt="Electronics">
                    <h3>Electronics</h3>
                    <a href="products.php?category=electronics" class="btn btn-outline">Shop Electronics</a>
                </div>
                <div class="category-card">
                    <img src="Image/clothing.png" alt="Clothing">
                    <h3>Clothing</h3>
                    <a href="products.php?category=clothing" class="btn btn-outline">Shop Clothing</a>
                </div>
                <div class="category-card">
                    <img src="Image/Home & Garden.jpg" alt="Home & Garden">
                    <h3>Home & Garden</h3>
                    <a href="products.php?category=home" class="btn btn-outline">Shop Home</a>
                </div>
                <div class="category-card">
                    <img src="Image/Sports.jpg" alt="Sports">
                    <h3>Sports</h3>
                    <a href="products.php?category=sports" class="btn btn-outline">Shop Sports</a>
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
