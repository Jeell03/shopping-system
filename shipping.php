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
    <title>Shipping Information - ShopEasy</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Custom styles for the Shipping page, reusing general layouts */
        .shipping-section {
            padding: 4rem 0;
            background: white;
            min-height: 80vh;
        }

        .shipping-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .shipping-header h1 {
            color: #2c3e50;
            margin-bottom: 0.5rem;
            font-size: 3rem;
        }

        .shipping-header p {
            color: #666;
            font-size: 1.2rem;
            max-width: 800px;
            margin: 0 auto;
        }

        .policy-container {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 4rem;
        }

        .policy-sidebar {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 10px;
            height: fit-content;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .policy-sidebar h3 {
            color: #3498db;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .policy-sidebar ul {
            list-style: none;
            padding: 0;
        }

        .policy-sidebar li {
            padding: 8px 0;
            font-weight: 500;
            color: #555;
            transition: color 0.2s;
        }

        .policy-content {
            padding-right: 20px;
        }

        .policy-content h2 {
            color: #2c3e50;
            margin-top: 0;
            margin-bottom: 1.5rem;
            padding-bottom: 10px;
            border-bottom: 1px dashed #e1e8ed;
        }

        .policy-section {
            margin-bottom: 3rem;
        }

        .policy-section h3 {
            color: #34495e;
            margin-bottom: 1rem;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .policy-section p, .policy-section ul {
            line-height: 1.7;
            color: #555;
        }

        .policy-section ul {
            list-style: disc;
            margin-left: 20px;
        }

        .rate-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border-radius: 8px;
            overflow: hidden;
        }

        .rate-table th, .rate-table td {
            border: 1px solid #e1e8ed;
            padding: 12px 15px;
            text-align: left;
        }

        .rate-table th {
            background-color: #3498db;
            color: white;
            font-weight: 600;
        }

        .rate-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        /* Responsive Design */
        @media (max-width: 992px) {
            .policy-container {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            .policy-sidebar {
                order: 1;
            }
            .policy-content {
                order: 2;
                padding-right: 0;
            }
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
            <span>Shipping Information</span>
        </div>
    </div>

    <!-- Shipping Section -->
    <section class="shipping-section">
        <div class="container">
            <div class="shipping-header">
                <h1>Shipping & Delivery Policy</h1>
                <p>Everything you need to know about how your order is processed, shipped, and delivered.</p>
            </div>

            <div class="policy-container">
                
                <div class="policy-sidebar">
                    <h3>Policy Overview</h3>
                    <ul>
                        <li><a href="#rates">Shipping Rates & Fees</a></li>
                        <li><a href="#time">Processing & Delivery Time</a></li>
                        <li><a href="#tracking">Order Tracking</a></li>
                        <li><a href="#restrictions">Shipping Restrictions</a></li>
                    </ul>
                    <a href="returns.php" class="btn btn-outline" style="margin-top: 20px; width: 100%;"><i class="fas fa-undo"></i> View Returns Policy</a>
                </div>

                <div class="policy-content">
                    
                    <div class="policy-section" id="rates">
                        <h2><i class="fas fa-dollar-sign"></i> Shipping Rates & Fees</h2>
                        <p>We offer simple and transparent shipping rates based on your order value. **Free shipping is available for all orders meeting the minimum threshold.**</p>
                        
                        <table class="rate-table">
                            <thead>
                                <tr>
                                    <th>Order Subtotal</th>
                                    <th>Shipping Rate (Standard)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Under $50.00</td>
                                    <td>$9.99 (Flat Rate)</td>
                                </tr>
                                <tr>
                                    <td>$50.00 and above</td>
                                    <td>**FREE**</td>
                                </tr>
                            </tbody>
                        </table>
                        <p style="font-size: 0.9em; margin-top: 10px; color: #777;">*Note: Shipping costs are calculated before tax and applied discounts.</p>
                    </div>
                    
                    <div class="policy-section" id="time">
                        <h2><i class="fas fa-clock"></i> Processing & Delivery Time</h2>
                        <p>Our goal is to get your products to you as quickly as possible. Please note the difference between processing time and shipping time.</p>
                        
                        <h3>Processing Time</h3>
                        <ul>
                            <li>**Standard:** 1-2 business days.</li>
                            <li>Orders are processed Monday through Friday, excluding public holidays.</li>
                        </ul>

                        <h3>Estimated Delivery Time</h3>
                        <table class="rate-table">
                            <thead>
                                <tr>
                                    <th>Service</th>
                                    <th>Estimated Transit Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Standard Shipping (Default)</td>
                                    <td>3-7 Business Days</td>
                                </tr>
                            </tbody>
                        </table>
                        <p style="font-size: 0.9em; margin-top: 10px; color: #777;">*Delivery times are estimates and may vary due to carrier delays or external factors.</p>
                    </div>

                    <div class="policy-section" id="tracking">
                        <h2><i class="fas fa-map-marker-alt"></i> Order Tracking</h2>
                        <p>Every order ships with a tracking number. Once your order is processed and leaves our warehouse, you will receive a confirmation email containing the tracking link. You can also find this information in your <a href="profile.php" style="color: #3498db; text-decoration: underline;">My Orders</a> page.</p>
                    </div>

                    <div class="policy-section" id="restrictions" style="border-bottom: none;">
                        <h2><i class="fas fa-ban"></i> Shipping Restrictions</h2>
                        <p>We currently only ship to addresses within the **United States** and **Canada**.</p>
                        <ul>
                            <li>We do not ship to P.O. boxes for large items.</li>
                            <li>Customers are responsible for any customs fees or duties for international shipments (Canada).</li>
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
</body>
</html>